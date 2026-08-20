<?php

namespace App\Listeners;

use App\Events\InvoiceIssued;
use App\Helpers\Notify;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class NotifyInvoiceIssued implements ShouldQueue
{
    use InteractsWithQueue;

    public $queue = 'redis';

    public $tries = 3;

    /**
     * Notifica a los administradores cuando una factura es emitida (además del email al cliente).
     */
    public function handle(InvoiceIssued $event): void
    {
        $invoice = $event->invoice;

        if (!$invoice) {
            return;
        }

        $label = $invoice->increment_id ?? ('#' . $invoice->id);
        $total = number_format((float) ($invoice->total ?? 0), 0, ',', '.');
        $customerName = $invoice->customer
            ? trim(($invoice->customer->first_name ?? '') . ' ' . ($invoice->customer->last_name ?? ''))
            : null;

        Notify::notifyInfo(
            "Factura {$label} emitida por \${$total}" . ($customerName ? " — {$customerName}" : ''),
            'Factura emitida',
            null,
            ['invoice_id' => $invoice->id]
        );
    }
}
