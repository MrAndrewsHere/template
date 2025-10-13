<?php

declare(strict_types=1);

namespace App\Service\Tasks\PipeHandlers;

use App\Models\Task;
use App\Service\Enums\TaskStatusEnum;
use Closure;

class HandleStatus
{
    public function handle(Task $task, Closure $next): Task
    {
        if (! $task->status) {
            $task->status = TaskStatusEnum::NEW;
        }

        if ($task->isHighPriority()) {

            $task->status = TaskStatusEnum::IN_PROGRESS;
        }

        return $next($task);
    }
}
