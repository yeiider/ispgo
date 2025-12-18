<?php

namespace App\GraphQL\Mutations;

use App\Models\TicketComment;
use Illuminate\Support\Facades\Auth;

class TicketCommentMutation
{
    /**
     * Add a comment to a ticket
     */
    public function add($_, array $args)
    {
        $comment = TicketComment::create([
            'ticket_id' => $args['ticket_id'],
            'user_id' => Auth::id(),
            'comment' => $args['comment'],
            'is_internal' => $args['is_internal'] ?? false,
        ]);

        return $comment->fresh(['ticket', 'user']);
    }

    /**
     * Update a ticket comment
     */
    public function update($_, array $args)
    {
        $comment = TicketComment::findOrFail($args['id']);

        // Only allow the comment author to update
        if ($comment->user_id !== Auth::id()) {
            throw new \Exception('You are not authorized to update this comment');
        }

        $comment->update([
            'comment' => $args['comment']
        ]);

        return $comment->fresh(['ticket', 'user']);
    }

    /**
     * Delete a ticket comment
     */
    public function delete($_, array $args)
    {
        $comment = TicketComment::findOrFail($args['id']);

        // Only allow the comment author to delete
        if ($comment->user_id !== Auth::id()) {
            throw new \Exception('You are not authorized to delete this comment');
        }

        $comment->delete();

        return [
            'success' => true,
            'message' => 'Comment deleted successfully'
        ];
    }
}
