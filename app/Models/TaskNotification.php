<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Traits\HasBelongsToTask;
use App\Models\Traits\HasBelongsToUser;
use Database\Factories\TaskNotificationFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class TaskNotification extends Base
{
    /** @use HasFactory<TaskNotificationFactory> */
    use HasBelongsToTask, HasBelongsToUser, HasFactory, Notifiable;

    protected $fillable = ['user_id', 'task_id', 'message'];

    protected $casts = [
        'message' => 'array',
    ];
}
