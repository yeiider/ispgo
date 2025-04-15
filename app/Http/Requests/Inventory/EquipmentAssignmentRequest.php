<?php

namespace App\Http\Requests\Inventory;

use Illuminate\Foundation\Http\FormRequest;

class EquipmentAssignmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id' => 'integer',
            'product_id' => 'integer',
            'assigned_at' => 'date',
            'returned_at' => 'date',
            'status' => 'string|max:255',
            'condition_on_assignment' => 'string|max:255',
            'condition_on_return' => 'string|max:255',
            'notes' => 'string',
        ];
    }
}
