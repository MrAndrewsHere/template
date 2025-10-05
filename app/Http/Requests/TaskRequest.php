<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Service\Enums\TaskStatusEnum;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Enum;

class TaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'min:3'],
            'status' => ['string', new Enum(TaskStatusEnum::class)],
            'due_date' => ['date', 'after_or_equal:today'],

        ];
    }
}
