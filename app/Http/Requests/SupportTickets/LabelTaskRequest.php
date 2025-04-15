<?php

namespace App\Http\Requests\SupportTickets;

use Illuminate\Foundation\Http\FormRequest;

class LabelTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'label_id' => 'integer',
            'task_id' => 'integer',
        ];
    }
}
