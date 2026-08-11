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
        $inv = $event->invoice;

        // No agregar líneas de servicio a facturas manuales
        if ($inv->invoice_type === 'manual' || $inv->invoice_type === 'adjustment') {
            return;
        }

        $services = $inv->customer->activeServices;
        $inv->recalcTotals();
        $inv->update(['state' => 'building']);

        event(new InvoiceItemsCreated($inv));
    }
}
