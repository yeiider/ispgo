<?php

namespace App\Http\Requests\SupportTickets;

use Illuminate\Foundation\Http\FormRequest;

class TaskCommentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'task_id' => 'integer',
            'user_id' => 'integer',
            'content' => 'string',
        ];
    }
}
