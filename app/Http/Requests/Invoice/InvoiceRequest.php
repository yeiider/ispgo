<?php

namespace App\Http\Requests\Invoice;

use Illuminate\Foundation\Http\FormRequest;

class InvoiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'increment_id' => 'string|max:255|unique:invoices,increment_id',
            'service_id' => 'integer',
            'customer_id' => 'integer',
            'customer_name' => 'string|max:255',
            'user_id' => 'integer',
            'subtotal' => 'numeric',
            'tax' => 'numeric',
            'total' => 'numeric',
            'amount' => 'numeric',
            'discount' => 'numeric',
            'outstanding_balance' => 'numeric',
            'issue_date' => 'date',
            'due_date' => 'date',
            'status' => 'in:paid,unpaid,overdue,canceled',
            'payment_method' => 'string|max:255',
            'notes' => 'string',
            'payment_support' => 'string|max:255',
            'daily_box_id' => 'integer',
            'created_by' => 'integer',
            'updated_by' => 'integer',
            'additional_information' => 'json',
        ];
    }
}
