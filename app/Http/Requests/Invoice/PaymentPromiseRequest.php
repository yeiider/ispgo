<?php

namespace App\Http\Requests\Invoice;

use Illuminate\Foundation\Http\FormRequest;

class PaymentPromiseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'invoice_id' => 'integer',
            'customer_id' => 'integer',
            'user_id' => 'integer',
            'amount' => 'numeric',
            'promise_date' => 'date',
            'notes' => 'string',
            'status' => 'in:pending,fulfilled,cancelled',
        ];
    }
}
