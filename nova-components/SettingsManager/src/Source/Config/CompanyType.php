<?php

namespace Ispgo\SettingsManager\Source\Config;

use Ispgo\SettingsManager\Source\ConfigProviderInterface;

class CompanyType implements ConfigProviderInterface
{

    static public function getConfig(): array
    {
        return [
            ["label" => "ISP", "value" => "isp"],
            ["label" => "Ecommerce", "value" => "ecommerce"],
            ["label" => "Sport", "value" => "sport"],
        ];
    }
}
