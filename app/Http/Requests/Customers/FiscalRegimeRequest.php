<?php

namespace App\Http\Requests\Customers;

use Illuminate\Foundation\Http\FormRequest;

class FiscalRegimeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'code' => 'string|max:255|unique:fiscal_regimes,code',
            'name' => 'string|max:255',
        ];
    }
}
