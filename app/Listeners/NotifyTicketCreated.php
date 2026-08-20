<?php

namespace App\Listeners;

use App\Events\TicketCreated;
use App\Helpers\Notify;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class NotifyTicketCreated implements ShouldQueue
{
    use InteractsWithQueue;

    public $queue = 'redis';

    public $tries = 3;

    /**
     * Notifica a los administradores cuando se crea un ticket.
     */
    public function handle(TicketCreated $event): void
    {
        $ticket = $event->ticket;

        if (!$ticket) {
            return;
        }

        $customerName = $ticket->customer
            ? trim(($ticket->customer->first_name ?? '') . ' ' . ($ticket->customer->last_name ?? ''))
            : null;

        $message = "Ticket creado: {$ticket->title}";
        if ($customerName) {
            $message .= " — {$customerName}";
        }

        Notify::notifyInfo(
            $message,
            'Nuevo ticket',
            null,
            ['ticket_id' => $ticket->id, 'customer_id' => $ticket->customer_id]
        );
    }
}
