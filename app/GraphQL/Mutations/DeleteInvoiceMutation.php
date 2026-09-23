<?php

namespace App\GraphQL\Mutations;

use App\Models\Invoice\Invoice;

class DeleteInvoiceMutation
{
    /**
     * Delete an invoice (Soft Delete)
     *
     * @param null $_
     * @param array $args
     * @return array
     */
    public function resolve($_, array $args): array
    {
        $invoice = Invoice::findOrFail($args['id']);

        if ($invoice->status === Invoice::STATUS_PAID) {
            throw new \Exception('No se puede eliminar una factura que se encuentra pagada.');
        }

        $invoice->delete();

        return [
            'success' => true,
            'message' => 'Factura eliminada exitosamente.',
        ];
    }
}
