<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Service\Enums\DealStatus;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class StoreDealRequest extends FormRequest
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
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'client_name' => ['required', 'string', 'max:30'],
            'client_phone' => ['required', 'string', 'max:20', 'regex:/^\+?[0-9\s\-\(\)]{7,20}$/'],
            'comment' => ['nullable', 'string', 'max:500'],
            'status' => ['nullable', 'string', new Enum(DealStatus::class)],
        ];
    }

    public function messages(): array
    {
        return [
            'product_id.required' => __('validation.deal.product_id.required'),
            'product_id.exists' => __('validation.deal.product_id.exists'),
            'client_name.required' => __('validation.deal.client_name.required'),
            'client_name.max' => __('validation.deal.client_name.max'),
            'client_phone.required' => __('validation.deal.client_phone.required'),
            'client_phone.max' => __('validation.deal.client_phone.max'),
            'comment.max' => __('validation.deal.comment.max'),
            'status.in' => __('validation.deal.status.in'),
        ];
    }
}
