<?php

namespace Ispgo\Siigo\Settings\Sources;

use Ispgo\SettingsManager\Source\ConfigProviderInterface;

class StampInvoiceOptions implements ConfigProviderInterface
{
    static public function getConfig(): array
    {
        return [
            ["label" => "Ninguna factura (Crear sin presentar a la DIAN)", "value" => "none"],
            ["label" => "Todas las facturas pagadas (Presentar a la DIAN solo al pagarse)", "value" => "paid_only"],
            ["label" => "Todas las facturas (Presentar siempre a la DIAN al crearse)", "value" => "all"],
        ];
    }
}
