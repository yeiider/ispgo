<?php

namespace App\Http\Resources\SupportTickets;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LabelTaskResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'label_id' => $this->label_id,
            'task_id' => $this->task_id,
        ];
    }
}
