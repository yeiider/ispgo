<?php

namespace App\GraphQL\Mutations;

use App\Models\Invoice\Invoice;
use Ispgo\Siigo\Jobs\CreateSiigoInvoice;
use Ispgo\Siigo\Settings\ConfigProviderSiigo;

class BulkSyncInvoicesToSiigoMutation
{
    public function resolve($_, array $args): array
    {
        $invoiceIds = $args['invoice_ids'] ?? [];

        if (empty($invoiceIds)) {
            return [
                'success' => false,
                'message' => __('No se seleccionó ninguna factura.'),
            ];
        }

        $invoices = Invoice::whereIn('id', $invoiceIds)->get();

        if ($invoices->isEmpty()) {
            return [
                'success' => false,
                'message' => __('No se encontraron las facturas especificadas.'),
            ];
        }

        $count = 0;
        foreach ($invoices as $invoice) {
            $scopeId = (int) ($invoice->router_id ?? 0);

            if (!ConfigProviderSiigo::getEnabled($scopeId)) {
                return [
                    'success' => false,
                    'message' => __('La integración con Siigo no está habilitada.'),
                ];
            }

            CreateSiigoInvoice::dispatch($invoice, true)->onQueue('redis');
            $count++;
        }

        return [
            'success' => true,
            'message' => __('Se enviaron :count factura(s) a sincronizar con Siigo en segundo plano.', ['count' => $count]),
        ];
    }
}
