<?php

namespace App\Listeners;

use App\Events\UserAssignedToTicket;
use App\Helpers\Notify;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class NotifyUserAssignedToTicket implements ShouldQueue
{
    use InteractsWithQueue;

    public $queue = 'redis';

    public $tries = 3;

    /**
     * Notifica a los administradores cuando un ticket es asignado.
     */
    public function handle(UserAssignedToTicket $event): void
    {
        $ticket = $event->ticket;

        if (!$ticket) {
            return;
        }

        Notify::notifyInfo(
            "Ticket asignado: {$ticket->title}",
            'Ticket asignado',
            null,
            ['ticket_id' => $ticket->id]
        );
    }
}
