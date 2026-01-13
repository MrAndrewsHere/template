<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Service\Enums\DealStatus;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class UpdateDealRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'comment' => ['nullable', 'string', 'max:500'],
            'status' => ['nullable', 'string', new Enum(DealStatus::class)],
        ];
    }

    public function messages(): array
    {
        return [
            'comment.max' => __('validation.deal.comment.max'),
            'status.in' => __('validation.deal.status.in'),
        ];
    }
}
