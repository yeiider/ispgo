<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TicketRequest extends FormRequest
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
            'issue_type' => 'string|max:255',
            'priority' => 'in:low,medium,high,urgent',
            'status' => 'in:open,in_progress,resolved,closed',
            'title' => 'string|max:255',
            'description' => 'string',
            'closed_at' => 'date',
            'user_id' => 'integer',
            'resolution_notes' => 'string',
            'attachments' => 'string',
            'contact_method' => 'string|max:255',
        ];
    }
}
