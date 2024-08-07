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
                    "label" => "Pay by whatsapp",
                    "placeholder" => "pay by whatsapp",
                ],
                /*"create_ticket" => [
                    "field" => "boolean-field",
                    "label" => "Create Ticket",
                    "placeholder" => "Create Ticket",
                ],*/
                "notify_payment" => [
                    "field" => "boolean-field",
                    "label" => "Notificar Payment",
                    "placeholder" => "Notificar Payment",
                ],
                "notify_payment_template" => [
                    "field" => "textarea-field",
                    "label" => "Notificar Payment Template",
                    "placeholder" => "Notificar Payment Template",
                ],
                "notify_invoice" => [
                    "field" => "boolean-field",
                    "label" => "Notify Invoice",
                ],
                "notify_invoice_template" => [
                    "field" => "textarea-field",
                    "label" => "Notificar Invoice Template",
                    "placeholder" => "Notificar Invoice Template",
                ],
                "env" => [
                    "field" => "select-field",
                    "label" => "Environment",
                    "placeholder" => "Environment",
                    "options" => \App\Settings\Config\Sources\Environment::class,
                ],
                "telephone_test" => [
                    "field" => "text-field",
                    "label" => "Telephone Prefix",
                    "placeholder" => "+57",
                ],
            ];
    }
}
