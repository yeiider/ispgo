<?php

namespace Ispgo\SettingsManager\Source\Config;

use Ispgo\SettingsManager\Source\ConfigProviderInterface;

class Yesno implements ConfigProviderInterface
{

    static public function getConfig(): array
    {
        return [
            ["label" => "Yes", "value" => "1"],
            ["label" => "No", "value" => "0"],
        ];
    }
}
