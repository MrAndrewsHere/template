<?php

declare(strict_types=1);

namespace App\Service\Tasks\PipeHandlers;

use App\Models\Task;
use App\Service\Enums\NotificationTypeEnum;
use Closure;

class HighPriority
{
    public function handle(Task $task, Closure $next): Task
    {
        if ($task->isHighPriority()) {
            $task->sendTaskNotification(NotificationTypeEnum::TASK_ASSIGNED);
        }

        return $next($task);
    }
}
