<?php

declare(strict_types=1);

namespace App\Service\Tasks\PipeHandlers;

use App\Models\Task;
use App\Service\Enums\NotificationTypeEnum;
use Closure;

class StatusChanged
{
    public function handle(Task $task, Closure $next): Task
    {
        if ($task->wasChanged('status') || $task->isDirty('status')) {
            $task->sendTaskNotification(NotificationTypeEnum::STATUS_CHANGED);
        }

        return $next($task);
    }
}
