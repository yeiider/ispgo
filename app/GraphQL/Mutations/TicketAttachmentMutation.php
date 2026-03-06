<?php

namespace App\GraphQL\Mutations;

use App\Models\TicketAttachment;
use Illuminate\Support\Facades\Auth;

class TicketAttachmentMutation
{
    /**
     * Add an attachment to a ticket
     */
    public function add($_, array $args)
    {
        $attachment = TicketAttachment::create([
            'ticket_id' => $args['ticket_id'],
            'user_id' => Auth::id(),
            'file_name' => $args['file_name'],
            'file_path' => $args['file_path'],
            'file_type' => $args['file_type'] ?? null,
            'file_size' => $args['file_size'] ?? null,
        ]);

        return $attachment->fresh(['ticket', 'user']);
    }

    /**
     * Delete a ticket attachment
     */
    public function delete($_, array $args)
    {
        $attachment = TicketAttachment::findOrFail($args['id']);

        // Only allow the attachment uploader to delete
        if ($attachment->user_id !== Auth::id()) {
            throw new \Exception('You are not authorized to delete this attachment');
        }

        // Optional: Delete physical file from storage
        // Storage::delete($attachment->file_path);

        $attachment->delete();

        return [
            'success' => true,
            'message' => 'Attachment deleted successfully'
        ];
    }
}
