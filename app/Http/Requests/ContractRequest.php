<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContractRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'customer_id' => 'integer',
            'service_id' => 'integer',
            'start_date' => 'date',
            'end_date' => 'date',
            'is_signed' => 'integer',
            'signed_at' => 'date',
        ];
    }
}
