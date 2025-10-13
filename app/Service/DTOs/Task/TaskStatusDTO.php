<?php

declare(strict_types=1);

namespace App\Service\DTOs\Task;

use App\Service\Enums\TaskStatusEnum;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\EnumCast;
use Spatie\LaravelData\Data;

class TaskStatusDTO extends Data
{
    public function __construct(
        public int $user_id,
        #[WithCast(EnumCast::class, type: TaskStatusEnum::class)]
        public string|TaskStatusEnum $status,
    ) {}
}
