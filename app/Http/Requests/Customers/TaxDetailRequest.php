<?php

namespace App\Http\Requests\Customers;

use Illuminate\Foundation\Http\FormRequest;

class TaxDetailRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('id') ?: $this->route('tax_detail');

        return [
            'customer_id' => 'integer',
            'tax_identification_type' => 'string|max:5',
            'tax_identification_number' => 'string|max:255|unique:tax_details,tax_identification_number' . ($id ? ',' . $id : ''),
            'taxpayer_type' => 'string|max:255',
            'fiscal_regime' => 'string|max:255',
            'business_name' => 'string|max:255',
            'enable_billing' => 'integer',
            'send_notifications' => 'integer',
            'send_invoice' => 'integer',
            'created_by' => 'integer',
            'updated_by' => 'integer',
        ];
    }
}
