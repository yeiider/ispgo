<?php

namespace App\Http\Requests\Invoice;

use Illuminate\Foundation\Http\FormRequest;

class InvoicePaymentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'invoice_id' => 'required|exists:invoices,id',
            'amount' => 'required|numeric|min:0.01',
            'payment_date' => 'required|date',
            'payment_method' => 'nullable|string|in:cash,transfer,card,online',
            'reference_number' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'payment_support' => 'nullable|string',
            'additional_information' => 'nullable|array',
        ];
    }

    /**
     * Get custom attribute names for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'invoice_id' => 'factura',
            'amount' => 'monto',
            'payment_date' => 'fecha de pago',
            'payment_method' => 'método de pago',
            'reference_number' => 'número de referencia',
            'notes' => 'notas',
            'payment_support' => 'comprobante',
            'additional_information' => 'información adicional',
        ];
    }
}
