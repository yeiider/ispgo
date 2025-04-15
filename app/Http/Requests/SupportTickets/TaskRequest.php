<?php

namespace App\Http\Requests\SupportTickets;

use Illuminate\Foundation\Http\FormRequest;

class TaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'column_id' => 'integer',
            'title' => 'string|max:255',
            'description' => 'string',
            'created_by' => 'integer',
            'updated_by' => 'integer',
            'customer_id' => 'integer',
            'service_id' => 'integer',
            'due_date' => 'date',
            'priority' => 'string|max:255',
        ];
    }
}
