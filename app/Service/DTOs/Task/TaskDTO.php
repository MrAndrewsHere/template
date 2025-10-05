<?php

declare(strict_types=1);

namespace App\Service\DTOs\Task;

use App\Service\Enums\TaskStatusEnum;
use Illuminate\Support\Facades\Date;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;

class TaskDTO extends Data
{
    public function __construct(
        public string $title,
        #[WithCast(DateTimeInterfaceCast::class, format: 'Y-m-d')]
        public Date|string|null|Optional $due_date,
        public string|null|Optional $description,
        #[WithCast(TaskStatusEnum::class)]
        public string|null|Optional|TaskStatusEnum $status,

    ) {}

}
