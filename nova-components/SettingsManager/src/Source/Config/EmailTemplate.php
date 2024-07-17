<?php

namespace Ispgo\SettingsManager\Source\Config;

use Ispgo\SettingsManager\Source\ConfigProviderInterface;

class EmailTemplate implements ConfigProviderInterface
{

    static public function getConfig(): array
    {

        $templates = \App\Models\EmailTemplate::all();

        $options = [];
        foreach ($templates as $template) {
            $options[] = [
                'value' => $template->id,
                'label' => $template->name,
            ];
        }
       return $options;

    }
}
