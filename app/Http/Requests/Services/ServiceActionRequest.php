<?php

namespace App\Http\Requests\Services;

use Illuminate\Foundation\Http\FormRequest;

class ServiceActionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'service_id' => 'integer',
            'action_type' => 'in:installation,uninstallation',
            'action_date' => 'date',
            'action_notes' => 'string',
            'user_id' => 'integer',
            'status' => 'in:pending,in_progress,completed,failed',
            'created_by' => 'integer',
            'updated_by' => 'integer',
        ];
    }
}
