<?php

namespace Ispgo\Siigo\Settings\Sources;

use Ispgo\SettingsManager\Source\ConfigProviderInterface;

class SyncInvoiceOptions implements ConfigProviderInterface
{

    static public function getConfig(): array
    {
        return [
            ["label" => "Al crear factura", "value" => "all"],
            ["label" => "Al pagar factura", "value" => "pay"],
        ];
    }
}
