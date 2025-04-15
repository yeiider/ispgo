<?php

namespace App\Http\Requests\SupportTickets;

use Illuminate\Foundation\Http\FormRequest;

class LabelRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'string|max:255',
            'color' => 'string|max:255',
        ];
    }
}
