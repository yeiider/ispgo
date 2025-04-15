<?php

namespace App\Http\Requests\PageBuilder;

use Illuminate\Foundation\Http\FormRequest;

class PagesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'string|max:256',
            'layout' => 'string|max:256',
            'data' => 'string',
        ];
    }
}
