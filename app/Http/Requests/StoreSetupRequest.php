<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class StoreSetupRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'admin_name' => ['required', 'string', 'max:120'],
            'admin_email' => ['required', 'email:rfc', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::min(12)->mixedCase()->numbers()->symbols()],
        ];
    }

    public function messages(): array
    {
        return [
            'admin_email.unique' => 'An account already uses this administrator email.',
            'password.confirmed' => 'The password confirmation does not match.',
            'password.*' => 'Use at least 12 characters with uppercase, lowercase, a number, and a symbol.',
        ];
    }
}
