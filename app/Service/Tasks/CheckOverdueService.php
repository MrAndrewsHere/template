<?php

declare(strict_types=1);

namespace App\Service\Tasks;

use App\Jobs\SendTaskNotificationJob;
use App\Models\Builders\TaskBuilder;
use App\Models\Task;
use App\Service\DTOs\Task\TaskCommentDTO;
use App\Service\Enums\NotificationTypeEnum;
use App\Service\Interfaces\OverdueServiceInterface;
use App\Service\Interfaces\TaskServiceInterface;

class CheckOverdueService implements OverdueServiceInterface
{
    public function handle(): int
    {
        $count = $this->builder()->count();

        $this->builder()
            ->cursor()
            ->each($this->notify(...));

        return $count;
    }

    public function count(): int
    {
        return $this->builder()->count();
    }

    protected function notify(Task $task): void
    {
        app()->make(TaskServiceInterface::class)
            ->comment($task, TaskCommentDTO::from([
                'user_id' => $task->user_id,
                'comment' => $task->overdueMessage(),
            ]));

        SendTaskNotificationJob::dispatch($task->id, NotificationTypeEnum::OVERDUE);
    }

    protected function builder(): TaskBuilder
    {
        return Task::spatieQueryBuilder()->checkOverdue();
    }
}
