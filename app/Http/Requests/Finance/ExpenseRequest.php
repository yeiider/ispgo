<?php

namespace App\Http\Requests\Finance;

use Illuminate\Foundation\Http\FormRequest;

class ExpenseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'description' => 'string|max:255',
            'amount' => 'numeric',
            'date' => 'date',
            'payment_method' => 'string|max:255',
            'category' => 'string|max:255',
            'supplier_id' => 'integer',
        ];
    }
}
