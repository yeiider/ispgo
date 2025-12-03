<?php

namespace App\GraphQL\Mutations;

use App\Models\Ticket;

class TicketLabelMutation
{
    /**
     * Add a label to a ticket
     */
    public function add($_, array $args)
    {
        $ticket = Ticket::findOrFail($args['ticket_id']);
        $ticket->addLabel($args['name'], $args['color'] ?? '#3498db');

        return $ticket->fresh(['users', 'customer', 'service', 'comments', 'attachments']);
    }

    /**
     * Remove a label from a ticket
     */
    public function remove($_, array $args)
    {
        $ticket = Ticket::findOrFail($args['ticket_id']);
        $ticket->removeLabel($args['name']);

        return $ticket->fresh(['users', 'customer', 'service', 'comments', 'attachments']);
    }
}
