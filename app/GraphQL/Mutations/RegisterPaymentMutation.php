<?php

namespace App\GraphQL\Mutations;

use App\Models\Invoice\Invoice;
use Illuminate\Support\Facades\Log;

class RegisterPaymentMutation
{
    public function resolve($_, array $args)
    {
        try {
            $invoice = Invoice::find($args['invoice_id']);

            if (!$invoice) {
                return [
                    'success' => false,
                    'message' => __('Invoice not found.'),
                ];
            }

            if ($invoice->status === Invoice::STATUS_PAID) {
                return [
                    'success' => false,
                    'message' => __('This invoice has already been paid!'),
                ];
            }

            $paymentMethod = $args['payment_method'];
            $notes = $args['notes'] ?? null;

            $invoice->applyPayment(null, $paymentMethod, [], $notes, null);

            return [
                'success' => true,
                'message' => __('Payment generated successfully!'),
            ];

        } catch (\Exception $e) {
            Log::error('Error in RegisterPaymentMutation', [
                'invoice_id' => $args['invoice_id'] ?? null,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => __('Error registering payment: :message', ['message' => $e->getMessage()]),
            ];
        }
    }
}
