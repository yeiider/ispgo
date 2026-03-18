<?php

namespace App\GraphQL\Mutations;

use App\Models\Invoice\Invoice;
use App\Services\Payments\OnePay\OnePayHandler;
use Illuminate\Support\Facades\Log;

class OnePayMutation
{
    protected OnePayHandler $onePayHandler;

    public function __construct(OnePayHandler $onePayHandler)
    {
        $this->onePayHandler = $onePayHandler;
    }

    public function createPayment($_, array $args)
    {
        $invoice = Invoice::findOrFail($args['invoice_id']);

        try {
            $payment = $this->onePayHandler->createPayment($invoice);

            // Actualizar la factura con el ID del cobro si se generó correctamente
            if (isset($payment['data']) && isset($payment['data']['id'])) {
                $invoice->forceFill(['onepay_charge_id' => $payment['data']['id']])->save();
            } elseif (isset($payment['id'])) {
                $invoice->forceFill(['onepay_charge_id' => $payment['id']])->save();
            }

            return [
                'success' => true,
                'message' => 'Cobro creado exitosamente en OnePay.',
                'payment' => $payment
            ];
        } catch (\Exception $e) {
            Log::error('OnePay Mutation Error (createPayment): ' . $e->getMessage());
            return [
                'success' => false,
                'message' => $e->getMessage(),
                'payment' => null
            ];
        }
    }

    public function resendPayment($_, array $args)
    {
        $invoice = Invoice::findOrFail($args['invoice_id']);

        try {
            $this->onePayHandler->resendPayment($invoice);

            return [
                'success' => true,
                'message' => 'Cobro reenviado exitosamente a través de OnePay.',
                'payment' => null
            ];
        } catch (\Exception $e) {
            Log::error('OnePay Mutation Error (resendPayment): ' . $e->getMessage());
            return [
                'success' => false,
                'message' => $e->getMessage(),
                'payment' => null
            ];
        }
    }
}
