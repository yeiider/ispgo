<?php

namespace App\Http\Requests\Inventory;

use Illuminate\Foundation\Http\FormRequest;

class SupplierRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'string|max:255',
            'contact' => 'string|max:255',
            'document' => 'string|max:255|unique:suppliers,document',
            'description' => 'string',
            'country' => 'string|max:255',
            'city' => 'string|max:255',
            'postal_code' => 'string|max:255',
            'email' => 'string|max:255|unique:suppliers,email',
            'phone' => 'string|max:255',
        ];
    }
}
