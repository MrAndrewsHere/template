<?php

declare(strict_types=1);

namespace App\Models;

use App\Jobs\SendTaskNotificationJob;
use App\Models\Builders\TaskBuilder;
use App\Models\Traits\HasBelongsToUser;
use App\Service\Enums\NotificationTypeEnum;
use App\Service\Enums\PriorityEnum;
use App\Service\Enums\TaskStatusEnum;
use Carbon\Carbon;
use Database\Factories\TaskFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $user_id
 * @property string $title
 * @property string $description
 * @property TaskStatusEnum $status
 * @property PriorityEnum $priority
 * @property Carbon $due_date
 */
class Task extends Base
{
    /** @use HasFactory<TaskFactory> */
    use HasBelongsToUser, HasFactory;

    protected $fillable = ['user_id', 'title', 'description', 'status', 'priority'];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => TaskStatusEnum::class,
            'priority' => PriorityEnum::class,
        ];
    }

    public function comments(): HasMany
    {
        return $this->hasMany(TaskComment::class);
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(TaskNotification::class);
    }

    public function isHighPriority(): bool
    {
        return (bool) $this->priority?->is(PriorityEnum::HIGH);
    }

    public function isCancelled(): bool
    {
        return (bool) $this->status?->is(TaskStatusEnum::CANCELLED);
    }

    public function isCompleted(): bool
    {
        return (bool) $this->status?->is(TaskStatusEnum::COMPLETED);
    }

    public function overdueMessage(): string|array|null
    {
        return __('task.overdue', ['date' => $this->created_at?->format('d.m.Y')]);
    }

    /**
     * @phpstan-return TaskBuilder
     */
    public static function spatieQueryBuilder(): TaskBuilder
    {
        return TaskBuilder::for(static::query());
    }

    public function sendTaskNotification(NotificationTypeEnum $type): void
    {
        SendTaskNotificationJob::dispatch($this->id, $type)
            ->afterResponse()
            ->afterCommit();
    }
}
