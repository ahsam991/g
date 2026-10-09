<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EnquiryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'type' => ['nullable', Rule::in(['contact', 'counselling', 'callback', 'course'])],
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:190'],
            'phone' => ['nullable', 'string', 'max:40'],
            'country' => ['nullable', 'string', 'max:80'],
            'subject' => ['nullable', 'string', 'max:120'],
            'level' => ['nullable', 'string', 'max:60'],
            'message' => ['nullable', 'string', 'max:3000'],
            'preferred_date' => ['nullable', 'string', 'max:40'],
            // Honeypot: a real person leaves this hidden field empty.
            'website' => ['prohibited'],
        ];
    }

    public function messages(): array
    {
        return ['website.prohibited' => 'Submission rejected.'];
    }
}
