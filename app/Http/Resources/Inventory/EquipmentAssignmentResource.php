<?php

namespace App\Http\Resources\Inventory;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EquipmentAssignmentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'product_id' => $this->product_id,
            'assigned_at' => $this->assigned_at,
            'returned_at' => $this->returned_at,
            'status' => $this->status,
            'condition_on_assignment' => $this->condition_on_assignment,
            'condition_on_return' => $this->condition_on_return,
            'notes' => $this->notes,
        ];
    }
}
