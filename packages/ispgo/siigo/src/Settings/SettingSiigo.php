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
            "document_id" => [
                "field" => "text-field",
                "label" => "ID del Tipo de Documento de Factura (FV)",
                "placeholder" => "e.g. 24445"
            ],
            "payment_id" => [
                "field" => "text-field",
                "label" => "ID del Medio de Pago a Crédito",
                "placeholder" => "e.g. 12"
            ],
            "product_code" => [
                "field" => "text-field",
                "label" => "Código de Producto/Servicio por defecto",
                "placeholder" => "e.g. ISP01"
            ],
            "tax_id" => [
                "field" => "text-field",
                "label" => "ID de Impuesto por defecto (opcional)",
                "placeholder" => "e.g. 31779"
            ],
            "voucher_document_id" => [
                "field" => "text-field",
                "label" => "ID del Tipo de Documento de Recibo de Caja (RC)",
                "placeholder" => "e.g. 24446"
            ],
            "voucher_account_debit" => [
                "field" => "text-field",
                "label" => "Cuenta contable de Caja/Banco (Débito)",
                "placeholder" => "e.g. 11100501"
            ],
            "voucher_account_credit" => [
                "field" => "text-field",
                "label" => "Cuenta contable de Cartera/Cliente (Crédito)",
                "placeholder" => "e.g. 13050501"
            ],
            "credit_note_document_id" => [
                "field" => "text-field",
                "label" => "ID del Tipo de Documento de Nota Crédito (NC)",
                "placeholder" => "e.g. 24447"
            ],
            "seller_id" => [
                "field" => "text-field",
                "label" => "ID del Vendedor por defecto",
                "placeholder" => "e.g. 719"
            ],
            "cost_center" => [
                "field" => "text-field",
                "label" => "ID del Centro de Costo",
                "placeholder" => "e.g.941"
            ],
        ];
    }
}
