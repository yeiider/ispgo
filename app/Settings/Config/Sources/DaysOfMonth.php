<?php

namespace App\Settings\Config\Sources;

use Ispgo\SettingsManager\Source\ConfigProviderInterface;

class DaysOfMonth implements ConfigProviderInterface
{

    static public function getConfig(): array
    {
        $options = [];
        for ($day = 1; $day <= 31; $day++) {
            $options[] = [
                'value' => $day,
                'label' => "Día $day de cada mes",
            ];
        }
        return $options;
    }
}
