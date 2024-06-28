<?php

namespace Ispgo\SettingsManager\Source\Config;

use Ispgo\SettingsManager\Source\ConfigProviderInterface;

class Yesno implements ConfigProviderInterface
{

    static public function getConfig(): array
    {
        return [
            ["label" => "1", "value" => "Yes"],
            ["label" => "0", "value" => "No"],
        ];
    }
}
