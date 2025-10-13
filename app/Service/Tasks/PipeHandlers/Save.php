<?php

declare(strict_types=1);

namespace App\Service\Tasks\PipeHandlers;

use App\Models\Task;
use Closure;

class Save
{
    public function handle(Task $task, Closure $next): Task
    {
        $task->save();

        return $next($task);
    }
}
