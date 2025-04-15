<?php

namespace App\Http\Requests\Invoice;

use Illuminate\Foundation\Http\FormRequest;

class CreditNoteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'invoice_id' => 'integer',
            'user_id' => 'integer',
            'amount' => 'numeric',
            'issue_date' => 'date',
            'reason' => 'string',
        ];
    }
}
