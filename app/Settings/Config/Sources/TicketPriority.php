<?php

namespace App\Settings\Config\Sources;

use Ispgo\SettingsManager\Source\ConfigProviderInterface;

class TicketPriority implements ConfigProviderInterface
{

    static public function getConfig(): array
    {
        return [
            ["label" => "Low", "value" => "low"],
            ["label" => "Medium", "value" => "medium"],
            ["label" => "High", "value" => "high"],
        ];
    }
}
