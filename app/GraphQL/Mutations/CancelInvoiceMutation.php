<?php

namespace App\GraphQL\Mutations;

use App\Models\Invoice\Invoice;
use Illuminate\Support\Facades\Log;

class CancelInvoiceMutation
{
    /**
     * Cancel an invoice
     *
     * @param null $_
     * @param array $args
     * @return array
     */
    public function resolve($_, array $args)
    {
        try {
            $invoiceId = $args['invoice_id'];
            $invoice = Invoice::find($invoiceId);

            if (!$invoice) {
                return [
                    'success' => false,
                    'message' => __('La factura no existe.'),
                ];
            }

            if ($invoice->status === 'paid') {
                return [
                    'success' => false,
                    'message' => __('No se puede cancelar una factura que ya ha sido pagada.'),
                ];
            }

            if ($invoice->status === 'canceled') {
                return [
                    'success' => false,
                    'message' => __('La factura ya se encuentra cancelada.'),
                ];
            }

            $invoice->canceled();

            return [
                'success' => true,
                'message' => __('Factura cancelada exitosamente.'),
                'invoice' => $invoice,
            ];

        } catch (\Exception $e) {
            Log::error('Error en CancelInvoiceMutation', [
                'invoice_id' => $args['invoice_id'] ?? null,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'success' => false,
                'message' => __('Error al cancelar la factura: :message', ['message' => $e->getMessage()]),
            ];
        }
    }
}
