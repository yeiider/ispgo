<?php

namespace Ispgo\Siigo\Settings\Sources;

use Ispgo\SettingsManager\Source\ConfigProviderInterface;

class SyncCustomersOptions implements ConfigProviderInterface
{

    static public function getConfig(): array
    {
        return [
            ["label" => "Todos los clientes", "value" => "all"],
            ["label" => "Customer impuestos", "value" => "only_tax_anabled"],
        ];
    }
}
