<?php

namespace App\Http\Requests\Invoice;

use Illuminate\Foundation\Http\FormRequest;

class DailyInvoiceBalanceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'date' => 'date',
            'total_invoices' => 'integer',
            'paid_invoices' => 'integer',
            'total_subtotal' => 'numeric',
            'total_tax' => 'numeric',
            'total_amount' => 'numeric',
            'total_discount' => 'numeric',
            'total_outstanding_balance' => 'numeric',
            'total_revenue' => 'numeric',
        ];
    }
}
