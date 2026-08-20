<?php

namespace App\Listeners;

use App\Events\PaymentReceived;
use App\Helpers\Notify;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class NotifyPaymentReceived implements ShouldQueue
{
    use InteractsWithQueue;

    public $queue = 'redis';

    public $tries = 3;

    /**
     * Notifica a los administradores cuando se registra un pago.
     */
    public function handle(PaymentReceived $event): void
    {
        $payment = $event->payment;

        if (!$payment) {
            return;
        }

        $amount = number_format((float) ($payment->amount ?? 0), 0, ',', '.');
        $reference = $payment->reference ?? null;

        Notify::notifySuccess(
            "Se registró un pago por \${$amount}" . ($reference ? " (ref: {$reference})" : ''),
            'Pago recibido',
            null,
            [
                'payment_id'         => $payment->id,
                'credit_account_id'  => $event->creditAccount?->id,
            ]
        );
    }
}
