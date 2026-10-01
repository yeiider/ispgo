<?php

namespace App\GraphQL\Mutations;

use App\Models\Invoice\Invoice;
use App\PaymentMethods\Wompi;
use Illuminate\Support\Facades\Log;

class WompiMutation
{
    /**
     * Generate (or reuse if still valid) the Wompi payment link for an invoice.
     */
    public function generatePaymentLink($_, array $args)
    {
        $invoice = Invoice::findOrFail($args['invoice_id']);

        try {
            $link = Wompi::getPaymentLink($invoice);

            if (!$link) {
                throw new \Exception('No se pudo generar el enlace de pago de Wompi. Verifica la configuración de Wompi en Ajustes.');
            }

            return [
                'success' => true,
                'message' => 'Enlace de pago de Wompi generado exitosamente.',
                'payment_link' => $link,
                'reference' => $invoice->increment_id,
            ];
        } catch (\Throwable $e) {
            Log::error('Wompi Mutation Error (generatePaymentLink): ' . $e->getMessage());
            return [
                'success' => false,
                'message' => $e->getMessage(),
                'payment_link' => null,
                'reference' => $invoice->increment_id,
            ];
        }
    }
}
