<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class BookingRequest extends FormRequest
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
        $rules = [
            'full_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'inquiry_description' => ['required', 'string'],
            'service_id' => ['required', 'string', 'exists:services,id'], // Ensure service exists

            // These fields are always optional unless required by service logic
            'mother_name' => ['nullable', 'string', 'max:255'],
            'phone_number' => ['nullable', 'string', 'max:20'],
        ];

        // --- Custom Conditional Validation (Highly recommended for specific services) ---

        // Example 1: Require mother_name if Istekhara is selected
        if (str_contains($this->input('service_id'), 'ISTEKHARA')) {
            $rules['mother_name'][] = 'required';
        }

        // Example 2: Require phone_number for Rukiya Intensive
        if ($this->input('service_id') === 'RUKIYA_INTENSIVE') {
            $rules['phone_number'][] = 'required';
        }

        return $rules;
    }

    /**
     * Prepare the data for validation.
     * This is useful if you need to set a default or format data before validation.
     */
    protected function prepareForValidation()
    {
        // Add current customer_id if authenticated, otherwise keep it null
        $this->merge([
            'customer_id' => Auth::id(),
        ]);
    }
}
