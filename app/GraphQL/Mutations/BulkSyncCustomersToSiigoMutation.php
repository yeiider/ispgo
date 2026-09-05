<?php

namespace App\GraphQL\Mutations;

use App\Models\Customers\Customer;
use Ispgo\Siigo\Jobs\CreateSiigoCustomer;
use Ispgo\Siigo\Settings\ConfigProviderSiigo;

class BulkSyncCustomersToSiigoMutation
{
    public function resolve($_, array $args): array
    {
        $customerIds = $args['customer_ids'] ?? [];

        if (empty($customerIds)) {
            return [
                'success' => false,
                'message' => __('No se seleccionó ningún cliente.'),
            ];
        }

        $customers = Customer::whereIn('id', $customerIds)->get();

        if ($customers->isEmpty()) {
            return [
                'success' => false,
                'message' => __('No se encontraron los clientes especificados.'),
            ];
        }

        $count = 0;
        foreach ($customers as $customer) {
            $scopeId = (int) ($customer->router_id ?? 0);

            if (!ConfigProviderSiigo::getEnabled($scopeId)) {
                return [
                    'success' => false,
                    'message' => __('La integración con Siigo no está habilitada.'),
                ];
            }

            CreateSiigoCustomer::dispatch($customer, true)->onQueue('redis');
            $count++;
        }

        return [
            'success' => true,
            'message' => __('Se enviaron :count cliente(s) a sincronizar con Siigo en segundo plano.', ['count' => $count]),
        ];
    }
}
