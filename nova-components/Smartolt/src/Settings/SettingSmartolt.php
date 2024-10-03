<?php

namespace Ispgo\Smartolt\Settings;

class SettingSmartolt
{

    public static function getGeneralSettings(): array
    {
        return [
            "setting" => [
                "label" => "General Information",
                "code" => "general"
            ],
            "enabled" => [
                "field" => "boolean-field",
                "label" => "Enabled",
                "placeholder" => "Enabled",
            ],
            "url" => [
                "field" => "text-field",
                "label" => "Url",
                "placeholder" => "https://globalraices.smartolt.com/",
            ],
            "token" => [
                "field" => "text-field",
                "label" => "Token",
                "placeholder" => "xxxxxxxxxxxxxxxxxxxxxxx",
            ],
            "customer_enabled" => [
                "field" => "boolean-field",
                "label" => "Habilitar y deshabilitar clientes",
                "placeholder" => "Enabled",
            ],
        ];
    }
}
