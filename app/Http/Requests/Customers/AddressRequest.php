<?php

namespace App\Http\Requests\Customers;

use Illuminate\Foundation\Http\FormRequest;

class AddressRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'customer_id' => 'integer',
            'customer_name' => 'string|max:255',
            'address' => 'string|max:100',
            'city' => 'string|max:100',
            'state_province' => 'string|max:100',
            'postal_code' => 'string|max:100',
            'country' => 'string|max:100',
            'address_type' => 'in:billing,shipping',
            'latitude' => 'numeric',
            'longitude' => 'numeric',
            'created_by' => 'integer',
            'updated_by' => 'integer',
        ];
    }
}
