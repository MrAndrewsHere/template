<?php

declare(strict_types=1);

namespace App\Notifications\Channels;

use App\Notifications\TaskAlertNotification;
use Illuminate\Support\Facades\Log;

class LogChannel
{
    public function send(object $notifiable, TaskAlertNotification $notification): void
    {
        Log::channel('notifications')->info('Notification', $notification->toLog($notifiable));
    }
}
