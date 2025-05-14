<?php
namespace Ispgo\Siigo\Settings;

class SettingSiigo
{
    public static function getGeneralSettings(): array
    {
        return [
            "setting" => [
                "label" => "Siigo API",
                "code"  => "general"
            ],
            "enabled" => [
                "field" => "boolean-field",
                "label" => "Habilitar integración"
            ],
            "environment" => [
                "field"   => "select-field",
                "label"   => "Ambiente",
                "options" => \App\Settings\Config\Sources\Environment::class,
            ],
            "base_url" => [
                "field" => "text-field",
                "label" => "URL base",
                "placeholder" => "https://api.siigo.com/"
            ],
            "username" => [
                "field" => "text-field",
                "label" => "Usuario API",
                "placeholder" => "EMPRESA\\usuario_api"
            ],
            "access_key" => [
                "field" => "password-field",
                "label" => "Access Key"
            ],
            "partner_id" => [
                "field" => "text-field",
                "label" => "Partner-Id (opcional)"
            ],
            "sync_customer" => [
                "field" => "boolean-field",
                "label" => "Sincronizar clientes"
            ],
            "sync_invoice" => [
                "field" => "boolean-field",
                "label" => "Sincronizar facturas"
            ],
            "sync_invoice_trigger" => [
                "field" => "select-field",
                "label" => "Cuando sincronizar facturas",
                "options" =>  \Ispgo\Siigo\Settings\Sources\SyncInvoiceOptions::class,
            ],
            "sync_customers_trigger" => [
                "field" => "select-field",
                "label" => "Cuando sincronizar facturas",
                "options" =>  \Ispgo\Siigo\Settings\Sources\SyncCustomersOptions::class,
            ],
        ];
    }
}
