<?php

namespace Ispgo\Mikrotik\Settings\Config\Sources;
use Ispgo\SettingsManager\Source\ConfigProviderInterface;

class ServicesType implements ConfigProviderInterface
{

    static public function getConfig(): array
    {
        return [
            ["label" => "PPPoE", "value" => "pppoe"],
            ["label" => "Any", "value" => "any"],
            ["label" => "Async", "value" => "async"],
            ["label" => "L2TP", "value" => "l2tp"],
            ["label" => "OVPN", "value" => "ovpn"],
            ["label" => "PPTP", "value" => "pptp"],
            ["label" => "SSTP", "value" => "sstp"],
        ];
    }
}
