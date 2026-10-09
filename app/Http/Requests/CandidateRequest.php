<?php

namespace App\Http\Requests;

use App\Models\Candidate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CandidateRequest extends FormRequest
{
    public function authorize(): bool
    {
        $candidate = $this->route('candidate');

        return $candidate
            ? $this->user()->can('update', $candidate)
            : $this->user()->can('create', Candidate::class);
    }

    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:80'],
            'last_name' => ['required', 'string', 'max:80'],
            'email' => ['required', 'email', 'max:190'],
            'phone' => ['nullable', 'string', 'max:40'],
            'alt_phone' => ['nullable', 'string', 'max:40'],
            'date_of_birth' => ['nullable', 'date', 'before:today'],
            'gender' => ['nullable', Rule::in(['male', 'female', 'other', 'prefer_not_to_say'])],
            'nationality' => ['nullable', 'string', 'max:80'],
            'country_of_residence' => ['nullable', 'string', 'max:80'],
            'passport_number' => ['nullable', 'string', 'max:40'],
            'passport_expiry' => ['nullable', 'date', 'after:today'],
            'marital_status' => ['nullable', 'string', 'max:20'],
            'preferred_destination' => ['nullable', 'string', 'max:80'],
            'preferred_level' => ['nullable', 'string', 'max:60'],
            'preferred_subject' => ['nullable', 'string', 'max:120'],
            'preferred_intake' => ['nullable', 'string', 'max:40'],
            'budget_per_year' => ['nullable', 'numeric', 'min:0', 'max:9999999'],
            'budget_currency' => ['nullable', 'string', 'size:3'],
            'assigned_to' => ['nullable', 'exists:users,id'],
            'branch_id' => ['nullable', 'exists:branches,id'],
            'status' => ['nullable', 'string', 'max:40'],
            'source' => ['nullable', 'string', 'max:60'],
            'notes' => ['nullable', 'string', 'max:5000'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'email' => strtolower(trim((string) $this->input('email'))),
            'passport_number' => strtoupper(trim((string) $this->input('passport_number'))) ?: null,
        ]);
    }
}
