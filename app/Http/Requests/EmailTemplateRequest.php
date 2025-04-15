<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmailTemplateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'string|max:255',
            'subject' => 'string|max:255',
            'body' => 'string',
            'styles' => 'string',
            'entity' => 'string|max:255',
            'is_active' => 'integer',
            'created_by' => 'integer',
            'updated_by' => 'integer',
            'test_email' => 'string|max:255',
            'description' => 'string',
        ];
    }
}
