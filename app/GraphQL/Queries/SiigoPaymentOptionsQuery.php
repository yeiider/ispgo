<?php

namespace App\GraphQL\Queries;

use Ispgo\Siigo\Settings\ConfigProviderSiigo;

class SiigoPaymentOptionsQuery
{
    public function resolve($_, array $args): array
    {
        $paymentMethod = $args['payment_method'] ?? 'cash';
        $scopeId = (int) ($args['router_id'] ?? 0);

        $isEnabled = ConfigProviderSiigo::getEnabled($scopeId);
        if (!$isEnabled) {
            return [
                'is_siigo_enabled' => false,
                'options' => [],
            ];
        }

        $options = ConfigProviderSiigo::getVoucherPaymentOptions($paymentMethod, $scopeId);

        return [
            'is_siigo_enabled' => true,
            'options' => $options,
        ];
    }
}
