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
//        $services->each(function ($service) use ($inv) {
//            $inv->items()->create([
//                'service_id' => $service->id,
//                'description' => $service->plan->name,
//                'unit_price' => $service->plan->monthly_price,
//                'quantity' => 1,
//                'subtotal' => $service->plan->monthly_price,
//            ]);
//        });

        $inv->recalcTotals();
        $inv->update(['state' => 'building']);

        event(new InvoiceItemsCreated($inv));
    }
}
