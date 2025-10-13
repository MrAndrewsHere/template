<?php

declare(strict_types=1);

namespace App\Service\Interfaces;

use App\Models\TaskNotification;
use App\Models\User;
use App\Service\Enums\NotificationTypeEnum;

interface TaskNotificationServiceInterface
{
    public function notifyManagers(NotificationTypeEnum $type): void;

    public function notifyUserWithAlert(User $user, NotificationTypeEnum $type): void;

    public function makeNotificate(array|string $msg): TaskNotification;
}
