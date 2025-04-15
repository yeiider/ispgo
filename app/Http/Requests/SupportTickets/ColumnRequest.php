<?php

namespace App\Http\Requests\SupportTickets;

use Illuminate\Foundation\Http\FormRequest;

class ColumnRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'board_id' => 'integer',
            'title' => 'string|max:255',
            'position' => 'integer',
        ];
    }
}
