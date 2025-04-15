<?php

namespace App\Http\Resources\SupportTickets;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'column_id' => $this->column_id,
            'title' => $this->title,
            'description' => $this->description,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'customer_id' => $this->customer_id,
            'service_id' => $this->service_id,
            'due_date' => $this->due_date,
            'priority' => $this->priority,
        ];
    }
}
