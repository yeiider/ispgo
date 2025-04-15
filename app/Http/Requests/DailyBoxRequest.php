<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DailyBoxRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'box_id' => 'integer',
            'date' => 'date',
            'start_amount' => 'numeric',
            'end_amount' => 'numeric',
        ];
    }
}
