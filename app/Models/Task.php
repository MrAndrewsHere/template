<?php

declare(strict_types=1);

namespace App\Models;

use App\Service\DTOs\Task\TaskDTO;
use App\Service\Enums\TaskStatusEnum;
use Carbon\Carbon;
use Database\Factories\TaskFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $user_id
 * @property string $title
 * @property string $description
 * @property TaskStatusEnum $status
 * @property Carbon $due_date
 */
class Task extends Model
{
    /** @use HasFactory<TaskFactory> */
    use HasFactory;

    protected $fillable = ['title', 'description', 'status', 'due_date'];

    protected $dataClass = TaskDTO::class;

    protected $dates = [
        'due_date',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => TaskStatusEnum::class,
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
