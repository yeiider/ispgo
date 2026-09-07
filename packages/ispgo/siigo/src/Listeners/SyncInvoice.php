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
        $invoice = $event->invoice;
        $customer = $invoice->customer;
        $scopeId = (int) ($invoice->router_id ?? $customer?->router_id ?? 0);

        if (!ConfigProviderSiigo::getEnabled($scopeId) || !ConfigProviderSiigo::getSyncInvoice($scopeId)) {
            return;
        }

        if (!$customer || !$customer->taxDetails || !$customer->taxDetails->enable_billing) {
            return;
        }

        if (ConfigProviderSiigo::getSyncInvoiceTrigger($scopeId) === 'all') {
            CreateSiigoInvoice::dispatch($invoice)->delay(now()->addSeconds(10))->onQueue('redis');
        }
    }

    public function onPaid(InvoicePaid $event): void
    {
        $invoice = $event->invoice;
        $customer = $invoice->customer;
        $scopeId = (int) ($invoice->router_id ?? $customer?->router_id ?? 0);

        if (!ConfigProviderSiigo::getEnabled($scopeId) || !ConfigProviderSiigo::getSyncInvoice($scopeId)) {
            return;
        }

        if ($event->invoice->status !== 'paid') {
            return;
        }

        if (!$customer || !$customer->taxDetails || !$customer->taxDetails->enable_billing) {
            return;
        }

        PaySiigoInvoice::dispatch($invoice, (float)$invoice->total)->delay(now()->addSeconds(5))->onQueue('redis');
    }

    public function onCanceled(InvoiceCanceled $event): void
    {
        $invoice = $event->invoice;
        $customer = $invoice->customer;
        $scopeId = (int) ($invoice->router_id ?? $customer?->router_id ?? 0);

        if (!ConfigProviderSiigo::getEnabled($scopeId) || !ConfigProviderSiigo::getSyncInvoice($scopeId)) {
            return;
        }

        if (!$customer || !$customer->taxDetails || !$customer->taxDetails->enable_billing) {
            return;
        }

        CancelSiigoInvoice::dispatch($invoice)->delay(now()->addSeconds(5))->onQueue('redis');
    }

    public function onDiscountApplied(\App\Events\InvoiceDiscountApplied $event): void
    {
        $invoice = $event->invoice;
        $customer = $invoice->customer;
        $scopeId = (int) ($invoice->router_id ?? $customer?->router_id ?? 0);

        if (!ConfigProviderSiigo::getEnabled($scopeId) || !ConfigProviderSiigo::getSyncInvoice($scopeId)) {
            return;
        }

        if (!$customer || !$customer->taxDetails || !$customer->taxDetails->enable_billing) {
            return;
        }

        $info = $invoice->additional_information ?? [];
        if (!empty($info['siigo_invoice_id'])) {
            \Ispgo\Siigo\Jobs\CreateSiigoDiscountCreditNote::dispatch(
                $invoice,
                (float) $event->discountAmount,
                (string) $event->description
            )->delay(now()->addSeconds(5))->onQueue('redis');
        }
    }
}
