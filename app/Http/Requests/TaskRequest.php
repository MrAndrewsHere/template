<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Service\Enums\PriorityEnum;
use App\Service\Enums\TaskStatusEnum;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class TaskRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'user_id' => ['nullable', 'exists:users,id'],
            'title' => ['required', 'string', 'min:3'],
            'description' => ['nullable', 'string'],
            'status' => ['nullable', 'string', new Enum(TaskStatusEnum::class)],
            'priority' => ['nullable', 'string', new Enum(PriorityEnum::class)],

        ];
    }
}
