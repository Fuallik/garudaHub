<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Validation\Rule;

class RegisterRequest extends IndonesianRequest
{
    protected function prepareForValidation(): void
    {
        $email = $this->input('email');

        if (is_string($email)) {
            $this->merge(['email' => strtolower(trim($email))]);
        }
    }

    public function rules(): array
    {
        return [
            'nama' => ['required', 'string', 'max:100'],
            'email' => ['required', 'string', 'email', 'max:100', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'max:72'],
            'role' => ['required', Rule::in(User::SELF_ROLES)],
        ];
    }

    public function attributes(): array
    {
        return [
            'nama' => 'Nama',
            'email' => 'Email',
            'password' => 'Password',
            'role' => 'Peran',
        ];
    }
}
