<?php

namespace Ispgo\Mikrotik\Settings\Config\Sources;
use Ispgo\SettingsManager\Source\ConfigProviderInterface;

class QueueType implements ConfigProviderInterface
{

    static public function getConfig(): array
    {
        return [
            ["label" => "Default", "value" => "default"],
            ["label" => "PCQ", "value" => "pcq"],
            ["label" => "FIFO", "value" => "fifo"],
        ];
    }
}
