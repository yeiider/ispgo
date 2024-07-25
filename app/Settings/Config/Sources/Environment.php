<?php

namespace App\Settings\Config\Sources;

use Ispgo\SettingsManager\Source\ConfigProviderInterface;

class Environment implements ConfigProviderInterface
{

    static public function getConfig(): array
    {
        return [
            ["label" => "Sandbox", "value" => "sandbox"],
            ["label" => "Production", "value" => "production"],
        ];
    }
}
