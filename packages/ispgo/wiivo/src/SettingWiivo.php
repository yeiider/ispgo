<?php

namespace Ispgo\Wiivo;

class SettingWiivo
{
    public static function getSetting(): array
    {
        return
            [
                "setting" => [
                    "label" => "Wiivo",
                    "class" => "form-control",
                    "code" => "wiivo"
                ],
                "enabled" => [
                    "field" => "boolean-field",
                    "label" => "Enabled",
                    "placeholder" => "Enabled",
                ],
                "url_api" => [
                    "field" => "text-field",
                    "label" => "Url Api",
                    "placeholder" => "Url Api",
                ],
                "api_key" => [
                    "field" => "text-field",
                    "label" => "Api Key",
                    "placeholder" => "Api Key",
                ],
                "telephone_prefix" => [
                    "field" => "text-field",
                    "label" => "Telephone Prefix",
                    "placeholder" => "+57",
                ],
                "session_life" => [
                    "field" => "number-field",
                    "label" => "Session Life",
                    "placeholder" => "5",
                ],

                "welcome_message" => [
                    "field" => "textarea-field",
                    "label" => "Welcome Message",
                    "placeholder" => "Welcome Message",
                ],
                "check_invoice" => [
                    "field" => "boolean-field",
                    "label" => "Check invoice",
                    "placeholder" => "Check invoice",
                ],
                "pay_by_whatsapp" => [
                    "field" => "boolean-field",
                    "label" => "pay by whatsapp",
                    "placeholder" => "pay by whatsapp",
                ],
                "create_ticket" => [
                    "field" => "boolean-field",
                    "label" => "Create Ticket",
                    "placeholder" => "Create Ticket",
                ],
            ];
    }
}
