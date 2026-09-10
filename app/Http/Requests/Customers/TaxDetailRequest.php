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
            'customer_id' => 'sometimes|required|integer|exists:customers,id',
            'tax_identification_type' => 'nullable|string|max:50',
            'tax_identification_number' => 'sometimes|required|string|max:255|unique:tax_details,tax_identification_number' . ($id ? ',' . $id : ''),
            'taxpayer_type' => 'sometimes|required|string|max:255',
            'fiscal_regime' => 'sometimes|required|string|max:255',
            'business_name' => 'nullable|string|max:255',
            'enable_billing' => 'nullable|integer',
            'send_notifications' => 'nullable|integer',
            'send_invoice' => 'nullable|integer',
            'created_by' => 'nullable|integer',
            'updated_by' => 'nullable|integer',
        ];
    }
}
