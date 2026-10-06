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

            // Se permite cancelar facturas pagadas. La lógica de contrapartida (Invoice::canceled())
            // se encargará de restar el balance a la caja actual del responsable.

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
