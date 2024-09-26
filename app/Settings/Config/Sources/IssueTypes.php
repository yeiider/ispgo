<?php

namespace App\Settings\Config\Sources;

use Ispgo\SettingsManager\Source\ConfigProviderInterface;

class IssueTypes implements ConfigProviderInterface
{

    static public function getConfig(): array
    {
        return [
            ["label" => "Connectivity", "value" => "connectivity"],
            ["label" => "Billing", "value" => "billing"],
            ["label" => "Configuration", "value" => "configuration"]
        ];
    }
}
