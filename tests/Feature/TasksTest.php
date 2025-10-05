<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class TasksTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        Sanctum::actingAs($this->user);
    }

    public function test_validation(): void
    {
        $task = Task::factory()->withDueDate()->forUser($this->user)->make();

        // title
        $task->title = null;
        $this->postJson(route('tasks.store'), $task->toArray())->assertStatus(422);

        // status
        $data = array_merge($task->toArray(), ['status' => 'Unknown', 'title' => 'Hello']);
        $this->postJson(route('tasks.store'), $data)->assertStatus(422);

        // due_date
        $task = Task::factory()->overdue()->forUser($this->user)->make();
        $this->postJson(route('tasks.store'), $task->toArray())->assertStatus(422);
    }

    public function test_returns_forbidden(): void
    {
        $task = Task::factory()->create();

        $this->getJson(route('tasks.show', $task))->assertStatus(403);
        $this->putJson(route('tasks.update', $task->id), ['title' => 'Hello, Gate'])->assertStatus(403);
        $this->deleteJson(route('tasks.destroy', $task->id))->assertStatus(403);

    }

    public function test_tasks_list(): void
    {
        Task::factory()->forUser($this->user)->count(3)->create();

        $response = $this->getJson(route('tasks.index'));

        $response->assertOk()
            ->assertJsonStructure([
                'data' => [
                    ['id', 'title', 'description', 'status', 'due_date', 'user' => ['id']],
                ],
                'links' => ['prev', 'next'],
                'meta' => ['current_page', 'from', 'last_page', 'links', 'path', 'per_page', 'to', 'total'],
            ])
            ->assertJsonPath('meta.total', 3);
    }

    public function test_show_returns_task(): void
    {
        $task = Task::factory()->forUser($this->user)->create();

        $response = $this->getJson(route('tasks.show', $task));

        $response->assertOk()
            ->assertJsonStructure(['id', 'title', 'description', 'status', 'due_date', 'user' => ['id']])
            ->assertJsonPath('id', $task->id);
    }

    public function test_store_update_destroy_task(): void
    {
        $task = Task::factory()->withDueDate()->make();

        $create = $this->postJson(route('tasks.store'), $task->toArray());
        $create
            ->assertCreated()
            ->assertJsonPath('title', $task->title)
            ->assertJsonPath('user_id', $this->user->id);

        $id = $create->json('id');

        $task2 = Task::factory()->withDueDate()->make();

        $update = $this->putJson(route('tasks.update', $id), $task2->toArray());
        $update->assertOk()
            ->assertJsonPath('title', $task2->title)
            ->assertJsonPath('desciption', $task2->desciption)
            ->assertJsonPath('status.value', $task2->status->value);

        $delete = $this->deleteJson(route('tasks.destroy', $id));
        $delete->assertNoContent();

        $this->assertDatabaseMissing('tasks', ['id' => $id]);
    }
}
