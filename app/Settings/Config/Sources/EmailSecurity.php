<?php

namespace App\Settings\Config\Sources;

use Ispgo\SettingsManager\Source\ConfigProviderInterface;

class EmailSecurity implements ConfigProviderInterface
{

    static public function getConfig(): array
    {
        return [
            ["label" => "TSL", "value" => "tsl"],
            ["label" => "SSL", "value" => "ssl"],
        ];
    }
}
