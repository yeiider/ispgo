<?php

namespace App\GraphQL\Queries;

use App\Models\Ticket;

class TicketQuery
{
    /**
     * Get tickets assigned to the authenticated user
     */
    public function myTickets($_, array $args)
    {
        return Ticket::forAuthenticatedUser();
    }
}
