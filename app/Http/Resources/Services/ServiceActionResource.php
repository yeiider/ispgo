<?php

namespace App\Http\Resources\Services;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ServiceActionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'service_id' => $this->service_id,
            'action_type' => $this->action_type,
            'action_date' => $this->action_date,
            'action_notes' => $this->action_notes,
            'user_id' => $this->user_id,
            'status' => $this->status,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
        ];
    }
}
