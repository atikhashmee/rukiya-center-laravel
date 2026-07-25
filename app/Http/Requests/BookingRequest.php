<?php

namespace App\Http\Requests;

use App\Models\Service;
use Illuminate\Foundation\Http\FormRequest;

class BookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'full_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'inquiry_description' => ['required', 'string', 'max:2000'],
            'service_id' => ['required', 'integer', 'exists:services,id'],
            'mother_name' => ['nullable', 'string', 'max:255'],
            'phone_number' => ['nullable', 'string', 'max:50'],
        ];

        $service = Service::find($this->input('service_id'));

        if ($service) {
            if (str_contains($service->category, 'istekhara')) {
                $rules['mother_name'][] = 'required';
            }
            if ($service->category === 'rukiya') {
                $rules['phone_number'][] = 'required';
            }
        }

        return $rules;
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'customer_id' => auth('customer')->id(),
        ]);
    }
}
