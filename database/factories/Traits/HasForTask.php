<?php

declare(strict_types=1);

namespace Database\Factories\Traits;

use App\Models\Task;

trait HasForTask
{
    public function forTask(Task $task): static
    {
        return $this->state(function (array $attributes) use ($task): array {
            return [
                'task_id' => $task->id,
            ];
        });
    }
}
