<?php

namespace Ispgo\Siigo\Settings;

class SettingSiigo
{
    public static function getGeneralSettings(): array
    {
        return [
            "setting" => [
                "label" => "General",
                "code"  => "general"
            ],
            "enabled" => [
                "field" => "boolean-field",
                "label" => "Habilitar integración"
            ],
            "sync_customer" => [
                "field" => "boolean-field",
                "label" => "Sincronizar clientes"
            ],
            "sync_customers_trigger" => [
                "field" => "select-field",
                "label" => "Cuando sincronizar clientes",
                "options" => \Ispgo\Siigo\Settings\Sources\SyncCustomersOptions::class,
            ],
        ];
    }

    public static function getApiSettings(): array
    {
        return [
            "setting" => [
                "label" => "API",
                "code"  => "api"
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
        ];
    }

    public static function getInvoiceSettings(): array
    {
        return [
            "setting" => [
                "label" => "Facturas",
                "code"  => "invoices"
            ],
            "sync_invoice" => [
                "field" => "boolean-field",
                "label" => "Sincronizar facturas"
            ],
            "sync_invoice_trigger" => [
                "field" => "select-field",
                "label" => "Cuando sincronizar facturas",
                "options" => \Ispgo\Siigo\Settings\Sources\SyncInvoiceOptions::class,
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
            "credit_note_document_id" => [
                "field" => "text-field",
                "label" => "ID del Tipo de Documento de Nota Crédito (NC)",
                "placeholder" => "e.g. 24447"
            ],
        ];
    }

    public static function getVoucherSettings(): array
    {
        return [
            "setting" => [
                "label" => "Recibos de Caja",
                "code"  => "vouchers"
            ],
            "voucher_document_id" => [
                "field" => "text-field",
                "label" => "ID del Tipo de Documento de Recibo de Caja (RC)",
                "placeholder" => "e.g. 10597"
            ],
            "voucher_payment_id" => [
                "field" => "text-field",
                "label" => "ID del Medio de Pago (RC) General / Fallback",
                "placeholder" => 'e.g. 2719',
                "description" => 'ID numérico por defecto si no se define una opción por método.'
            ],
            "voucher_payment_id_efectivo" => [
                "field" => "text-field",
                "label" => "ID Medio de Pago (RC) Efectivo",
                "placeholder" => 'e.g. 5646 o {"Caja Principal": 5646, "Caja Auxiliar": 5647}',
                "description" => 'ID único numérico (ej: 5646) o JSON {"Nombre": ID} para múltiples opciones (ej: {"Caja 1": 5646, "Caja 2": 5647}).'
            ],
            "voucher_payment_id_transferencia" => [
                "field" => "text-field",
                "label" => "ID Medio de Pago (RC) Transferencia",
                "placeholder" => 'e.g. 5647 o {"Bancolombia 790": 5647, "BBVA 450": 5648}',
                "description" => 'ID único numérico (ej: 5647) o JSON {"Nombre": ID} para múltiples bancos (ej: {"Bancolombia 790": 8131, "BBVA 2332": 8150}). Si agregas 2 o más, se mostrará el selector desplegable en el Punto de Cobro.'
            ],
            "voucher_payment_id_tarjeta" => [
                "field" => "text-field",
                "label" => "ID Medio de Pago (RC) Tarjeta",
                "placeholder" => 'e.g. 5649 o {"Bold QA": 11101, "Tarjeta Débito": 11100}',
                "description" => 'ID único numérico (ej: 5649) o JSON {"Nombre": ID} para múltiples datáfonos (ej: {"Bold QA": 11101, "Tarjeta Débito QA": 11100}).'
            ],
            "voucher_payment_id_pago_online" => [
                "field" => "text-field",
                "label" => "ID Medio de Pago (RC) Pago Online",
                "placeholder" => 'e.g. 5650 o {"Wompi": 5650, "PayU": 5651}',
                "description" => 'ID único numérico (ej: 5650) o JSON {"Nombre": ID} para pasarelas online (ej: {"Wompi": 5650, "PayU": 5651}).'
            ],
        ];
    }

    public static function getOtherSettings(): array
    {
        return [
            "setting" => [
                "label" => "Otras configuraciones siigo",
                "code"  => "others"
            ],
            "cost_center" => [
                "field" => "text-field",
                "label" => "ID del Centro de Costo",
                "placeholder" => "e.g. 941"
            ],
            "seller_id" => [
                "field" => "text-field",
                "label" => "ID del Vendedor por defecto",
                "placeholder" => "e.g. 719"
            ],
        ];
    }
}
