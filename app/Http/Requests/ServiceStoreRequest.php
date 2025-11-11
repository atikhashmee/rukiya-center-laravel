<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class ServiceStoreRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'id_code' => [
                'required',
                'string',
                'uppercase',
                'regex:/^[A-Z0-9_]+$/', 
                Rule::unique('service_options', 'id_code') 
            ],
            'category' => [
                'required',
                'string',
                Rule::in(['counseling', 'rukiya', 'istekhara', 'other'])
            ],
            'order' => ['required', 'integer', 'min:1'],

            // Content & Design
            'title' => ['required', 'string', 'max:255'],
            'tagline' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'icon' => ['required', 'string', 'max:100'],
            'card_color' => ['required', 'string', 'max:100'],
            
            'features' => ['required', 'array'],
            'features.*' => ['string'], 
            
            // Pricing & Logic
            'price_type' => [
                'required',
                Rule::in(['FREE', 'DONATION', 'FIXED', 'RESERVATION'])
            ],
            'price_value' => [
                'nullable',
                'numeric',
                'min:0',
                'required_if:price_type,FIXED' 
            ],
            'min_donation' => [
                'nullable',
                'numeric',
                'min:0',
                'required_if:price_type,DONATION' // Required only if price_type is DONATION
            ],
            'requires_custom_assessment' => ['required', 'boolean'],

            // JSON Array Fields
            'required_form_fields' => ['nullable', 'array'],
            'required_form_fields.*' => ['string'],

            'submit_button_text' => ['required', 'string', 'max:100'],
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'price_value' => $this->price_type === 'FIXED' ? $this->price_value : null,
            'min_donation' => $this->price_type === 'DONATION' ? $this->min_donation : null,
        ]);
    }
}
