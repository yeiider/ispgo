<?php

namespace App\Settings\Config\Sources;

use Ispgo\SettingsManager\Source\ConfigProviderInterface;

class DocumentType implements ConfigProviderInterface
{
    static public function getConfig(): array
    {
        return \App\Models\Customers\DocumentType::all()->map(function ($item) {
            return [
                'label' => $item->name,
                'value' => $item->code,
            ];
        })->toArray();
    }
}
