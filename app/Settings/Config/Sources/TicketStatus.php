<?php

namespace App\Settings\Config\Sources;

use Ispgo\SettingsManager\Source\ConfigProviderInterface;

class TicketStatus implements ConfigProviderInterface
{

    static public function getConfig(): array
    {
        return [
            ["label" => "Open", "value" => "open"],
            ["label" => "In Progress", "value" => "in_progress"],
            ["label" => "Closed", "value" => "closed"],
        ];
    }
}
