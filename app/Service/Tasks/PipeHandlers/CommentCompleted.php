<?php

declare(strict_types=1);

namespace App\Service\Tasks\PipeHandlers;

use App\Models\Task;
use App\Models\User;
use App\Service\DTOs\Task\TaskCommentDTO;
use App\Service\Interfaces\TaskServiceInterface;
use Closure;

class CommentCompleted
{
    public function __construct(private readonly User $user) {}

    public function handle(Task $task, Closure $next): Task
    {
        if (! $task->isCompleted()) {
            return $next($task);
        }

        app()->make(TaskServiceInterface::class)
            ->comment($task, TaskCommentDTO::from([
                'user_id' => $this->user->id,
                'comment' => __('task.completed', ['user_name' => $this->user->name]),
            ]));

        return $next($task);
    }
}
