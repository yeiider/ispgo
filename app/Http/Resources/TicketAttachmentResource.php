<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TicketAttachmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'ticket_id' => $this->ticket_id,
            'comment_id' => $this->comment_id,
            'filename' => $this->filename,
            'original_filename' => $this->original_filename,
            'file_path' => $this->file_path,
            'url' => $this->url, // Uses the accessor defined in the model
            'mime_type' => $this->mime_type,
            'file_size' => $this->file_size,
            'human_file_size' => $this->human_file_size, // Uses the accessor defined in the model
            'is_image' => $this->is_image, // Uses the accessor defined in the model
            'uploaded_by' => $this->uploaded_by,
            'uploader' => [
                'id' => $this->uploader->id,
                'name' => $this->uploader->name,
            ],
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
