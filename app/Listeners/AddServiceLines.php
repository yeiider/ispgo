<?php

namespace App\Listeners;

use App\Events\InvoiceCreated;
use App\Events\InvoiceItemsCreated;


class AddServiceLines
{
    /**
     * Handle the event.
     */
    public function handle(InvoiceCreated $event): void
    {
        $services = $event->invoice->customer->activeServices;
        $inv = $event->invoice;
        $inv->recalcTotals();
        $inv->update(['state' => 'building']);

        event(new InvoiceItemsCreated($inv));
    }
}
