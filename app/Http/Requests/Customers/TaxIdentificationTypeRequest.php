<?php

namespace App\Http\Requests\Customers;

use Illuminate\Foundation\Http\FormRequest;

class TaxIdentificationTypeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'code' => 'string|max:255|unique:tax_identification_types,code',
            'name' => 'string|max:255',
        ];
    }
}
