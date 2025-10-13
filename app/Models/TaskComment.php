<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Traits\HasBelongsToTask;
use App\Models\Traits\HasBelongsToUser;
use Database\Factories\TaskCommentFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TaskComment extends Base
{
    /** @use HasFactory<TaskCommentFactory> */
    use HasBelongsToTask, HasBelongsToUser, HasFactory;

    protected $fillable = ['user_id', 'task_id', 'comment'];
}
