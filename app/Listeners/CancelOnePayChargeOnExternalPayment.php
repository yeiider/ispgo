<?php

namespace App\Listeners;

use App\Events\InvoicePaid;
use App\Services\Payments\OnePay\OnePayHandler;
use App\Settings\OnePaySettings;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class CancelOnePayChargeOnExternalPayment implements ShouldQueue
{
    use InteractsWithQueue;


    /**
     * The name of the queue the job should be sent to.
     *
     * @var string|null
     */
    public $queue = 'redis';

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 3;

    /**
     * The number of seconds the job can run before timing out.
     *
     * @var int
     */
    public $timeout = 120;

    /**
     * The number of seconds to delay the job.
     *
     * @var int
     */
    public $delay = 10;


    /**
     * Cuando una factura se paga por un medio DISTINTO a OnePay (efectivo,
     * transferencia, Nequi, PSE, etc.) hay que ELIMINAR la factura en OnePay.
     *
     * Antes este listener solo hacía `DELETE /payments/{onepay_charge_id}`: eso
     * cancela el cobro pero deja la FACTURA de OnePay viva en estado `CREATED`,
     * y es la factura la que sigue recordando al cliente por WhatsApp/email.
     * Por eso los clientes que ya habían pagado seguían recibiendo recordatorios.
     *
     * Ahora se elimina la FACTURA (`DELETE /invoices/{id}` con
     * reason=DELETE_FROM_PROVIDER), que además cancela su cobro asociado y no
     * envía ningún mensaje al cliente.
     */
    public function handle(InvoicePaid $event): void
    {
        $invoice = $event->invoice;

        // Si el pago SÍ vino de OnePay, no se borra nada: solo marcamos el estado.
        if (($invoice->payment_method ?? null) === 'onepay') {
            $invoice->forceFill(['onepay_status' => 'paid'])->save();
            return;
        }

        // Sin cobro/factura creado en OnePay no hay nada que limpiar.
        if (!$invoice->onepay_invoice_id && !$invoice->onepay_charge_id) {
            return;
        }

        if (!OnePaySettings::baseUrl() || !OnePaySettings::apiToken()) {
            Log::warning('OnePay settings missing; cannot cancel charge automatically.', [
                'invoice_id' => $invoice->id,
            ]);
            return;
        }

        try {
            $handler = app(OnePayHandler::class);
            $invoiceDeleted = $handler->deleteInvoiceForExternalPayment($invoice);

            if ($invoiceDeleted) {
                // Limpiar referencias locales para que no se reenvíe ni se reintente.
                $invoice->forceFill([
                    'onepay_invoice_id' => null,
                    'onepay_charge_id' => null,
                    'onepay_payment_link' => null,
                    'onepay_status' => 'deleted',
                ])->save();
            }
        } catch (\Throwable $e) {
            Log::error('Error eliminando la factura OnePay tras un pago externo: ' . $e->getMessage(), [
                'invoice_id' => $invoice->id,
                'onepay_invoice_id' => $invoice->onepay_invoice_id,
                'onepay_charge_id' => $invoice->onepay_charge_id,
            ]);
        }
    }
}
