<?php

declare(strict_types=1);

namespace App\Service\Tasks\PipeHandlers;

use App\Models\Task;
use App\Models\User;
use Closure;
use Exception;

class AssignManager
{
    public function handle(Task $task, Closure $next): Task
    {
        if ($task->user_id) {

            return $next($task);
        }

        $manager = User::query()
            ->manager()
            ->inRandomOrder()
            ->first();

        if (! $manager) {
            throw new Exception('Не удалось назначить пользователя: не найден ни один менеджер');
        }

        $task->user_id = $manager->id;

        return $next($task);
    }
}
