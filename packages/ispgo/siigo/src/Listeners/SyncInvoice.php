<?php
namespace Ispgo\Siigo\Listeners;
use Ispgo\Siigo\Jobs\CreateSiigoInvoice;

class SyncInvoice
{
    public function handle(\App\Events\InvoiceCreated $event): void
    {
        /*if (config('siigo.sync_invoice')) {
            CreateSiigoInvoice::dispatch($event->invoice->toArray());
        }*/
    }
}
