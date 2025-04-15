<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TicketResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'customer_id' => $this->customer_id,
            'service_id' => $this->service_id,
            'issue_type' => $this->issue_type,
            'priority' => $this->priority,
            'status' => $this->status,
            'title' => $this->title,
            'description' => $this->description,
            'closed_at' => $this->closed_at,
            'user_id' => $this->user_id,
            'resolution_notes' => $this->resolution_notes,
            'attachments' => $this->attachments,
            'contact_method' => $this->contact_method,
        ];
    }
}
