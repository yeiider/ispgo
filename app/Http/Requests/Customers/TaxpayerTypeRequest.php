<?php

namespace App\Http\Requests\Customers;

use Illuminate\Foundation\Http\FormRequest;

class TaxpayerTypeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'code' => 'string|max:255|unique:taxpayer_types,code',
            'name' => 'string|max:255',
        ];
    }
}
