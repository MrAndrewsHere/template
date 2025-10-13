<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\Task;
use App\Service\Enums\NotificationTypeEnum;
use App\Service\Interfaces\TaskNotificationServiceInterface;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SendTaskNotificationJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(public int $task_id, public NotificationTypeEnum $type)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $task = Task::query()->findOrFail($this->task_id);

        app()->makeWith(TaskNotificationServiceInterface::class, ['task' => $task])->notifyManagers($this->type);
    }
}
