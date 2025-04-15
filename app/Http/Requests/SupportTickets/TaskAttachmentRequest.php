<?php

namespace App\Http\Requests\SupportTickets;

use Illuminate\Foundation\Http\FormRequest;

class TaskAttachmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'task_id' => 'integer',
            'file_path' => 'string|max:255',
            'file_name' => 'string|max:255',
            'uploaded_by' => 'integer',
        ];
    }
}
