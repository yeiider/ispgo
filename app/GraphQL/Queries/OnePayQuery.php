<?php

namespace App\GraphQL\Queries;

use App\Services\Payments\OnePay\OnePayHandler;

class OnePayQuery
{
    protected OnePayHandler $onePayHandler;

    public function __construct(OnePayHandler $onePayHandler)
    {
        $this->onePayHandler = $onePayHandler;
    }

    public function getPayment($_, array $args)
    {
        $result = $this->onePayHandler->getPayment($args['payment_id']);

        // Manejo si la API devuelve un paginador (arreglo con la llave 'data')
        if (isset($result['data']) && is_array($result['data']) && count($result['data']) > 0) {
            return $result['data'][0];
        }

        // Manejo si la API devuelve el objeto directo
        if (isset($result['id'])) {
            return $result;
        }

        return null;
    }

    public function getPaymentIntents($_, array $args)
    {
        $result = $this->onePayHandler->getPaymentIntents($args['payment_id']);

        if (isset($result['data'])) {
            return $result['data'];
        }

        if (is_array($result)) {
            return $result;
        }

        return [];
    }

    public function getCustomerByDocument($_, array $args)
    {
        return $this->onePayHandler->getCustomerByDocument($args['document_number']);
    }
}
