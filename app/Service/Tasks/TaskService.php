<?php

declare(strict_types=1);

namespace App\Service\Tasks;

use App\Models\Task;
use App\Models\TaskComment;
use App\Models\User;
use App\Service\DTOs\Task\TaskCommentDTO;
use App\Service\DTOs\Task\TaskDTO;
use App\Service\DTOs\Task\TaskStatusDTO;
use App\Service\Interfaces\TaskPipelineInterface;
use App\Service\Interfaces\TaskServiceInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Validation\ValidationException;

class TaskService implements TaskServiceInterface
{
    public function index(): LengthAwarePaginator
    {
        return Task::spatieQueryBuilder()
            ->withComments()
            ->withUser()
            ->defaultSort('-created_at')
            ->paginate();
    }

    public function show(Task $task): Task
    {
        return Task::spatieQueryBuilder()
            ->withComments()
            ->withUser()
            ->findOrFail($task->id);
    }

    public function store(TaskDTO $DTO): Task
    {
        return $this
            ->pipeline(new Task($DTO->toArray()))
            ->handleStatus()
            ->assignManager()
            ->save()
            ->highPriority()
            ->then($this->show(...));
    }

    public function status(TaskStatusDTO $DTO, Task $task): Task
    {
        return $this
            ->pipeline($task->fill(['status' => $DTO->status]))
            ->save()
            ->statusChanged()
            ->commentCompleted(User::query()->findOrFail($DTO->user_id))
            ->then($this->show(...));
    }

    public function update(TaskDTO $DTO, Task $task): Task
    {
        return $task;
    }

    public function destroy(Task $task): void
    {
        $task->delete();
    }

    public function comment(Task $task, TaskCommentDTO $payload): TaskComment
    {
        if ($task->isCancelled()) {
            throw ValidationException::withMessages([
                'status' => [__('task.comment-not-allowed')],
            ]);
        }

        return TaskComment::create([
            'user_id' => $payload->user_id,
            'task_id' => $task->id,
            'comment' => $payload->comment,
        ]);
    }

    protected function pipeline(Task $task, array $handlers = []): TaskPipeline
    {
        return app(TaskPipelineInterface::class)
            ->send($task)
            ->through($handlers);
    }
}
