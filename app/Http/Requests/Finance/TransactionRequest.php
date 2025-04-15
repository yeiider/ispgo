<?php

namespace App\Http\Requests\Finance;

use Illuminate\Foundation\Http\FormRequest;

class TransactionRequest extends FormRequest
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
            'type' => 'string|max:255',
            'payment_method' => 'string|max:255',
            'category' => 'string|max:255',
            'cash_register_id' => 'integer',
        ];
    }
}
