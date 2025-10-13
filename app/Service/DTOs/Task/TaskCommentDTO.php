<?php

declare(strict_types=1);

namespace App\Service\DTOs\Task;

use Spatie\LaravelData\Data;

class TaskCommentDTO extends Data
{
    public function __construct(
        public int $user_id,
        public string $comment,
    ) {}
}
