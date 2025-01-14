<?php

namespace App\Settings\Config\Sources;

use Ispgo\SettingsManager\Source\ConfigProviderInterface;

class HtmlTemplate implements ConfigProviderInterface
{

    static public function getConfig(): array
    {
        return \App\Models\HtmlTemplate::all()->map(function ($template) {
            return [
                'label' => $template->name,
                'value' => $template->id,
            ];
        })->toArray();
    }
}
