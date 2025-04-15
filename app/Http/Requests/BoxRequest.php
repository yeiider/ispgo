<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BoxRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'string|max:255',
            'users' => 'string|max:255',
        ];
    }
}
