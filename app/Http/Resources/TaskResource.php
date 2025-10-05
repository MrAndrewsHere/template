<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /**
         * @var Task $task
         */
        $task = $this->resource;

        return [
            'id' => $task->id,
            'title' => $task->title,
            'description' => $task->description,
            'status' => [
                'label' => $task->status->label(),
                'value' => $task->status->value,
            ],
            'due_date' => $task->due_date,
            'user_id' => $task->user_id,
            'user' => $task->relationLoaded('user') ? UserResource::make($task->user) : null,
        ];
    }
}
