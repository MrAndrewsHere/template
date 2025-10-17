<?php

declare(strict_types=1);

namespace App\Service\Tasks;

use App\Models\Task;
use App\Models\TaskComment;
use App\Models\User;
use App\Service\DTOs\Task\TaskCommentDTO;
use App\Service\DTOs\Task\TaskDTO;
use App\Service\DTOs\Task\TaskStatusDTO;
use App\Service\Enums\NotificationTypeEnum;
use App\Service\Enums\TaskStatusEnum;
use App\Service\Interfaces\TaskServiceInterface;
use Exception;
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
        $task = new Task($DTO->toArray());

        $this->handleStatusAssign($task)
            ->handleUserAssign($task);

        $task->save();

        $this->handleHighPriority($task);

        return $this->show($task);
    }

    public function status(TaskStatusDTO $DTO, Task $task): Task
    {
        $task->fill(['status' => $DTO->status]);

        $task->save();

        $this->handleStatusChanged($task)
            ->commentIfCompleted($task,
                User::query()->findOrFail($DTO->user_id)
            );

        return $this->show($task);

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

    protected function handleStatusChanged(Task $task): static
    {
        if ($task->wasChanged('status') || $task->isDirty('status')) {
            $task->sendTaskNotification(NotificationTypeEnum::STATUS_CHANGED);
        }

        return $this;
    }

    protected function handleHighPriority(Task $task): static
    {
        if ($task->isHighPriority()) {
            $task->sendTaskNotification(NotificationTypeEnum::TASK_ASSIGNED);
        }

        return $this;
    }

    protected function handleStatusAssign(Task &$task): static
    {

        if (! $task->status) {
            $task->status = TaskStatusEnum::NEW;
        }

        if ($task->isHighPriority()) {

            $task->status = TaskStatusEnum::IN_PROGRESS;
        }

        return $this;

    }

    protected function handleUserAssign(Task &$task): static
    {

        if ($task->user_id) {

            return $this;
        }

        $manager = User::query()
            ->manager()
            ->inRandomOrder()
            ->first();

        if (! $manager) {
            throw new Exception('Не удалось назначить пользователя: не найден ни один менеджер');
        }

        $task->user_id = $manager->id;

        return $this;
    }

    protected function commentIfCompleted(Task $task, User $user): static
    {
        if (! $task->isCompleted()) {
            return $this;
        }

        $this->comment($task, TaskCommentDTO::from([
            'user_id' => $user->id,
            'comment' => __('task.completed', ['user_name' => $user->name]),
        ]));

        return $this;

    }
}
