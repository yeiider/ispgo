<?php

namespace App\Settings\Iptv;

class SettingIptv
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
                "label" => "Server URL",
                "placeholder" => "http://your-xui-domain.com:8000/",
            ],
            "access_code" => [
                "field" => "password-field",
                "label" => "Access Code",
                "placeholder" => "Enter access code",
            ],
            "api_key" => [
                "field" => "password-field",
                "label" => "API Key",
                "placeholder" => "Enter API key",
            ],
        ];
    }

    public static function getActivationSettings(): array
    {
        return [
            "setting" => [
                "label" => "Activation Defaults",
                "code" => "activation"
            ],
            "default_max_connections" => [
                "field" => "number-field",
                "label" => "Default Max Connections",
                "placeholder" => "1",
            ],
            "default_member_id" => [
                "field" => "number-field",
                "label" => "Default Reseller/Member ID",
                "placeholder" => "1",
            ],
            "default_bouquets" => [
                "field" => "text-field",
                "label" => "Default Bouquets (comma separated)",
                "placeholder" => "1,2",
            ],
        ];
    }
}
