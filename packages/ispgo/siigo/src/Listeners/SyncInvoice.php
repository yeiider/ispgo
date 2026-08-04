<?php
namespace Ispgo\Siigo\Listeners;

use App\Events\InvoiceCreated;
use App\Events\InvoicePaid;
use App\Events\InvoiceCanceled;
use Ispgo\Siigo\Jobs\CreateSiigoInvoice;
use Ispgo\Siigo\Jobs\PaySiigoInvoice;
use Ispgo\Siigo\Jobs\CancelSiigoInvoice;
use Ispgo\Siigo\Settings\ConfigProviderSiigo;

class SyncInvoice
{
    public function onCreated(InvoiceCreated $event): void
    {
        if (!ConfigProviderSiigo::getEnabled() || !ConfigProviderSiigo::getSyncInvoice()) {
            return;
        }

        $invoice = $event->invoice;
        $customer = $invoice->customer;
        if (!$customer || !$customer->taxDetails || !$customer->taxDetails->enable_billing) {
            return;
        }

        if (ConfigProviderSiigo::getSyncInvoiceTrigger() === 'all') {
            CreateSiigoInvoice::dispatch($invoice)->delay(now()->addSeconds(10))->onQueue('redis');
        }
    }

    public function onPaid(InvoicePaid $event): void
    {
        if (!ConfigProviderSiigo::getEnabled() || !ConfigProviderSiigo::getSyncInvoice()) {
            return;
        }

        if ($event->invoice->status !== 'paid') {
            return;
        }

        $invoice = $event->invoice;
        $customer = $invoice->customer;
        if (!$customer || !$customer->taxDetails || !$customer->taxDetails->enable_billing) {
            return;
        }

        PaySiigoInvoice::dispatch($invoice, (float)$invoice->total)->delay(now()->addSeconds(5))->onQueue('redis');
    }

    public function onCanceled(InvoiceCanceled $event): void
    {
        if (!ConfigProviderSiigo::getEnabled() || !ConfigProviderSiigo::getSyncInvoice()) {
            return;
        }

        $invoice = $event->invoice;
        $customer = $invoice->customer;
        if (!$customer || !$customer->taxDetails || !$customer->taxDetails->enable_billing) {
            return;
        }

        CancelSiigoInvoice::dispatch($invoice)->delay(now()->addSeconds(5))->onQueue('redis');
    }
}
