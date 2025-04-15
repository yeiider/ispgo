<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'string|max:255',
            'email' => 'string|max:255|unique:users,email',
            'email_verified_at' => 'date',
            'password' => 'string|max:255',
            'two_factor_secret' => 'string',
            'two_factor_recovery_codes' => 'string',
            'two_factor_confirmed_at' => 'date',
            'telephone' => 'string|max:255',
            'created_by' => 'integer',
            'updated_by' => 'integer',
            'remember_token' => 'string|max:100',
        ];
    }
}
