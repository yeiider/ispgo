<?php

namespace App\Http\Requests\Inventory;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'string|max:255',
            'sku' => 'string|max:255|unique:products,sku',
            'price' => 'numeric',
            'special_price' => 'numeric',
            'cost_price' => 'numeric',
            'brand' => 'string|max:255',
            'qty' => 'string|max:255',
            'image' => 'string|max:255',
            'description' => 'string',
            'reference' => 'string|max:255',
            'taxes' => 'numeric',
            'status' => 'integer',
            'url_key' => 'string|max:255|unique:products,url_key',
            'warehouse_id' => 'integer',
            'category_id' => 'integer',
        ];
    }
}
