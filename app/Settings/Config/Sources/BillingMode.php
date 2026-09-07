<?php

namespace App\Settings\Config\Sources;

use Ispgo\SettingsManager\Source\ConfigProviderInterface;

class BillingMode implements ConfigProviderInterface
{
    public static function getConfig(): array
    {
        return [
            [
                'value' => 'advance',
                'label' => 'Mes anticipado (Facturación por adelantado)',
            ],
            [
                'value' => 'arrears',
                'label' => 'Mes vencido (Facturación a mes vencido)',
            ],
        ];
    }
}
