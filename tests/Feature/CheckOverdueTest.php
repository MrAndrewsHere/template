<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Jobs\SendTaskNotificationJob;
use App\Models\Task;
use App\Service\Enums\NotificationTypeEnum;
use App\Service\Interfaces\OverdueServiceInterface;
use App\Service\Interfaces\TaskServiceInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class CheckOverdueTest extends TestCase
{
    use RefreshDatabase;

    public function test_overdue_command(): void
    {
        Queue::fake();

        $count = Task::factory()
            ->overdue()
            ->count(5)
            ->create()
            ->count();

        $this->mock(TaskServiceInterface::class)->shouldReceive('comment')->times($count);

        $this->artisan('tasks:check-overdue')
            ->expectsOutput(__('task.check-overdue', ['count' => $count]))
            ->assertExitCode(0);

        Queue::assertPushed(SendTaskNotificationJob::class, fn (SendTaskNotificationJob $job): bool => $job->type === NotificationTypeEnum::OVERDUE);
        Queue::assertCount($count);
    }

    public function test_overdue_command_dry_run(): void
    {
        $overdueServiceInterface = $this->mock(OverdueServiceInterface::class);
        $overdueServiceInterface->shouldReceive('count')->once();
        $overdueServiceInterface->shouldNotReceive('handle');

        $this->artisan('tasks:check-overdue --dry-run')
            ->expectsOutput(__('task.check-overdue', ['count' => 0]))
            ->assertExitCode(0);
    }
}
