<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Jobs\SendTaskNotificationJob;
use App\Models\Task;
use App\Models\TaskComment;
use App\Service\Enums\NotificationTypeEnum;
use App\Service\Enums\TaskStatusEnum;
use App\Service\Enums\UserPositionEnum;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class TasksTest extends TestCase
{
    use RefreshDatabase;

    public function test_default_sort(): void
    {
        $task = Task::factory()->create(['created_at' => now()]);
        $task2 = Task::factory()->create(['created_at' => now()->addMinute()]);
        $task3 = Task::factory()->create(['created_at' => now()->addMinutes(2)]);

        $response = $this->getJson(route('tasks.index'));

        $response->assertOk()
            ->assertJsonPath('meta.total', 3)
            ->assertJsonPath('data.0.id', $task3->id)
            ->assertJsonPath('data.1.id', $task2->id)
            ->assertJsonPath('data.2.id', $task->id);

    }

    public function test_validation(): void
    {
        $task = Task::factory()->make();

        $data = array_merge($task->toArray(), [
            'title' => null,
            'user_id' => '-1',
            'status' => 'Unknown',
            'priority' => 'Unknown',
        ]);

        $this->postJson(route('tasks.store'), $data)
            ->assertStatus(422)
            ->assertJsonValidationErrors([
                'title',
                'user_id',
                'status',
                'priority',
            ]);

    }

    public function test_tasks_list(): void
    {
        Task::factory()->count(3)->create();

        $response = $this->getJson(route('tasks.index'));

        $response->assertOk()
            ->assertJsonStructure([
                'data' => [
                    ['id', 'title', 'description', 'status', 'user' => ['id']],
                ],
                'links' => ['prev', 'next'],
                'meta' => ['current_page', 'from', 'last_page', 'links', 'path', 'per_page', 'to', 'total'],
            ])
            ->assertJsonPath('meta.total', 3);
    }

    public function test_show_returns_task(): void
    {
        $task = Task::factory()->create();

        $response = $this->getJson(route('tasks.show', $task));

        $response->assertOk()
            ->assertJsonStructure(['id', 'title', 'description', 'status', 'user' => ['id', 'name', 'position'], 'comments'])
            ->assertJsonPath('id', $task->id);
    }

    public function test_store_high_task(): void
    {
        Queue::fake();

        $task = Task::factory()->high()->recentlyCreated()->make();

        $create = $this->postJson(route('tasks.store'), $task->toArray());

        $create
            ->assertCreated()
            ->assertJsonStructure(['id', 'title', 'description', 'status', 'user' => ['id'], 'comments'])
            ->assertJsonPath('title', $task->title)
            ->assertJsonPath('user.id', $task->user->id)
            ->assertJsonPath('status.value', TaskStatusEnum::IN_PROGRESS->value); // При создании задачи с priority = "high" - автоматически назначить status = "in_progress".

        Queue::assertPushed(SendTaskNotificationJob::class, fn (SendTaskNotificationJob $job): bool => $job->type === NotificationTypeEnum::TASK_ASSIGNED);

    }

    public function test_store_task(): void
    {
        new UserSeeder()->managers();

        Queue::fake();

        $task = Task::factory()->normal()->make();

        $task->status = $task->user_id = null;

        $create = $this->postJson(route('tasks.store'), $task->toArray());
        $create
            ->assertCreated()
            ->assertJsonPath('user.position.value', UserPositionEnum::MANAGER->value) // Если user_id не указан, назначить задачу на пользователя с position = "manager" ;
            ->assertJsonPath('status.value', TaskStatusEnum::NEW->value); // Status по умолчанию = "new"

        Queue::assertNotPushed(SendTaskNotificationJob::class, fn (SendTaskNotificationJob $job): bool => $job->type === NotificationTypeEnum::TASK_ASSIGNED);

    }

    /**
     * При смене статуса запустить job для отправки уведомлений всем пользователям с position = "manager"
     */
    public function test_task_update_status(): void
    {
        Queue::fake();

        $task = Task::factory()->normal()->create();

        $this->putJson(route('tasks.status.update', $task->id), [
            'status' => $task->status->value,
            'user_id' => $task->user_id,
        ])->assertOk();

        $this->putJson(route('tasks.status.update', $task->id), [
            'status' => fake()->randomElement(TaskStatusEnum::notIn($task->status))->value,
            'user_id' => $task->user_id,
        ])->assertOk();

        Queue::assertPushed(SendTaskNotificationJob::class, fn (SendTaskNotificationJob $job): bool => $job->type === NotificationTypeEnum::STATUS_CHANGED);

        Queue::assertCount(1);
    }

    /**
     * При смене статуса на "completed" автоматически добавить комментарий
     */
    public function test_task_status_completed(): void
    {

        Queue::fake();

        $task = Task::factory()->normal()->recentlyCreated()->create();

        $this->putJson(route('tasks.status.update', $task->id), [
            'status' => TaskStatusEnum::COMPLETED->value,
            'user_id' => $task->user_id,
        ])->assertOk();

        $this->assertDatabaseHas(new TaskComment()->getTable(), [
            'task_id' => $task->id,
            'user_id' => $task->user_id,
        ]);

        Queue::assertPushed(SendTaskNotificationJob::class, fn (SendTaskNotificationJob $job): bool => $job->type === NotificationTypeEnum::STATUS_CHANGED);

        Queue::assertCount(1);
    }

    public function test_task_store_comment(): void
    {
        $task = Task::factory()->normal()->recentlyCreated()->create();

        $this->postJson(route('tasks.comments.store', $task->id), [
            'comment' => 'Привет',
            'user_id' => $task->user_id,
        ])->assertCreated();

        $this->assertDatabaseHas(new TaskComment()->getTable(), [
            'task_id' => $task->id,
            'user_id' => $task->user_id,
            'comment' => 'Привет',
        ]);

        $task->update(['status' => TaskStatusEnum::CANCELLED->value]); // Нельзя добавлять комментарии к задачам со status = "cancelled"

        $this->postJson(route('tasks.comments.store', $task->id), [
            'comment' => 'Привет',
            'user_id' => $task->user_id,
        ])->assertStatus(422)
            ->assertJsonValidationErrors(['status']);
    }
}
