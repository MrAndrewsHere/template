<?php

declare(strict_types=1);

namespace App\Service\DTOs\Task;

use App\Service\Enums\PriorityEnum;
use App\Service\Enums\TaskStatusEnum;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\EnumCast;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;

class TaskDTO extends Data
{
    public function __construct(
        public int|null|Optional $user_id,
        public string $title,
        public string|null|Optional $description,
        #[WithCast(EnumCast::class, type: TaskStatusEnum::class)]
        public string|null|Optional|TaskStatusEnum $status,
        #[WithCast(EnumCast::class, type: PriorityEnum::class)]
        public string|null|Optional|PriorityEnum $priority,

    ) {}

}
