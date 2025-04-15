<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class HtmlTemplateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'string|max:255|unique:html_templates,name',
            'body' => 'string',
            'styles' => 'string',
            'entity' => 'string|max:255',
        ];
    }
}
