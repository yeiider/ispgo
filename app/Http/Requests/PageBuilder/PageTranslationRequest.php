<?php

namespace App\Http\Requests\PageBuilder;

use Illuminate\Foundation\Http\FormRequest;

class PageTranslationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'page_id' => 'integer',
            'locale' => 'string|max:50',
            'title' => 'string|max:255',
            'meta_title' => 'string|max:255',
            'meta_description' => 'string|max:255',
            'route' => 'string|max:255',
        ];
    }
}
