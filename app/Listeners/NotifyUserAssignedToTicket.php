<?php

namespace App\Listeners;

use App\Models\Ticket;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class NotifyUserAssignedToTicket
{

    /**
     * Handle the event.
     */
    public function handle(Ticket $ticket): void
    {
        // Code
    }
}
