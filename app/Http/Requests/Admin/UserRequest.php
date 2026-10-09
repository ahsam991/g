<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasPermission('users.manage');
    }

    public function rules(): array
    {
        $userId = $this->route('user')?->id;

        return [
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:190', Rule::unique('users', 'email')->ignore($userId)],
            'phone' => ['nullable', 'string', 'max:40'],
            'role_id' => ['required', 'exists:roles,id'],
            'branch_id' => ['nullable', 'exists:branches,id'],
            'team_id' => ['nullable', 'exists:teams,id'],
            'status' => ['required', Rule::in(['active', 'suspended'])],
            'password' => [$userId ? 'nullable' : 'required', 'confirmed', Password::min(12)->mixedCase()->numbers()],
        ];
    }
}
