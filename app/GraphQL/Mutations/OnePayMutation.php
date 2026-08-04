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

            $data = isset($payment['data']) && is_array($payment['data']) ? $payment['data'] : $payment;

            $invoiceId = $data['id'] ?? null;
            $chargeId = $data['payment_id'] ?? $data['payment']['id'] ?? null;
            $paymentLink = $data['payment']['payment_link'] ?? $data['payment_link'] ?? null;
            $status = $data['status'] ?? 'pending';

            $invoice->forceFill([
                'onepay_invoice_id' => $invoiceId,
                'onepay_charge_id' => $chargeId,
                'onepay_payment_link' => $paymentLink,
                'onepay_status' => $status,
                'onepay_metadata' => $payment,
            ])->save();

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
