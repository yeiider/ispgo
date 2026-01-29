<?php

namespace App\GraphQL\Queries;

use App\Services\Invoice\InvoicePaymentService;

class InvoicePaymentQueries
{
    protected InvoicePaymentService $service;

    public function __construct(InvoicePaymentService $service)
    {
        $this->service = $service;
    }

    /**
     * Obtener abonos de una factura específica
     *
     * @param mixed $root
     * @param array<string, mixed> $args
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getByInvoice($root, array $args)
    {
        return $this->service->getByInvoiceId($args['invoiceId']);
    }
}
