<?php

namespace App\Settings\Config\Sources;

use Ispgo\SettingsManager\Source\ConfigProviderInterface;

class IssueTypes implements ConfigProviderInterface
{

    static public function getConfig(): array
    {
        return [
            ["label" => __("Connectivity"), "value" => "connectivity"],
            ["label" => __("Billing"), "value" => "billing"],
            ["label" => __("Configuration"), "value" => "configuration"]
        ];
    }
}
