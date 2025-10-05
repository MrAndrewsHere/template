<?php

declare(strict_types=1);

namespace App\Service;

use App\Models\Task;
use App\Models\User;
use App\Service\DTOs\Task\TaskDTO;
use App\Service\Interfaces\TaskServiceInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class TaskService implements TaskServiceInterface
{
    public function __construct(private readonly User $user) {}

    public function index(): LengthAwarePaginator
    {
        return $this->user
            ->tasks()
            ->with('user')
            ->paginate();
    }

    public function store(TaskDTO $DTO): Task
    {
        $task = new Task($DTO->toArray());

        $task->user_id = $this->user->id;
        $task->save();
        $task->load('user');

        return $task;
    }

    public function show(Task $task): Task
    {
        $this->user->tasks()->findOrFail($task->id);

        $task->load('user');

        return $task;
    }

    public function update(TaskDTO $DTO, Task $task): Task
    {
        $this->user->tasks()->findOrFail($task->id);

        $task->fill($DTO->toArray());
        $task->save();
        $task->load('user');

        return $task->refresh();
    }

    public function destroy(Task $task): void
    {
        $this->user->tasks()->findOrFail($task->id);

        $task->delete();
    }
}
