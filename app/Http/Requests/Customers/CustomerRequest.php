<?php

namespace App\Http\Requests\Customers;

use Illuminate\Foundation\Http\FormRequest;

class CustomerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'first_name' => 'string|max:100',
            'last_name' => 'string|max:100',
            'date_of_birth' => 'date',
            'phone_number' => 'string|max:12',
            'email_address' => 'string|max:255|unique:customers,email_address',
            'document_type' => 'string|max:5',
            'identity_document' => 'string|max:12',
            'customer_status' => 'in:active,inactive',
            'additional_notes' => 'string',
            'created_by' => 'integer',
            'updated_by' => 'integer',
            'password' => 'string|max:255',
            'password_reset_token' => 'string|max:255',
            'remember_token' => 'string|max:100',
            'password_reset_token_expiration' => 'date',
        ];
    }
}
