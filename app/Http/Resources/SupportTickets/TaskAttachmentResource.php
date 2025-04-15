<?php

namespace App\Http\Resources\SupportTickets;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskAttachmentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'task_id' => $this->task_id,
            'file_path' => $this->file_path,
            'file_name' => $this->file_name,
            'uploaded_by' => $this->uploaded_by,
        ];
    }
}
