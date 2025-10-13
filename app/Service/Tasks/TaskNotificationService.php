<?php

declare(strict_types=1);

namespace App\Service\Tasks;

use App\Models\Task;
use App\Models\TaskNotification;
use App\Models\User;
use App\Notifications\TaskAlertNotification;
use App\Service\Enums\NotificationTypeEnum;
use App\Service\Interfaces\TaskNotificationServiceInterface;
use Illuminate\Support\Arr;

class TaskNotificationService implements TaskNotificationServiceInterface
{
    public function __construct(public readonly Task $task) {}

    public function notifyManagers(NotificationTypeEnum $type): void
    {
        $this->managers()
            ->each($this->notifyUserWithOverdue(...));
    }

    public function notifyUserWithOverdue(User $user): void
    {
        $this->notifyUserWithAlert($user, NotificationTypeEnum::OVERDUE);
    }

    public function notifyUserWithAlert(User $user, NotificationTypeEnum $type): void
    {
        $this->makeNotificate([
            'notification_type' => $type->toArray(),
            'task' => $this->task->toArray(),
        ])
            ->notify(new TaskAlertNotification);
    }

    public function makeNotificate(array|string $msg): TaskNotification
    {
        return TaskNotification::create([
            'user_id' => $this->task->user_id,
            'task_id' => $this->task->id,
            'message' => Arr::wrap($msg),
        ]);
    }

    protected function managers()
    {
        return User::query()
            ->manager()
            ->cursor();
    }
}
