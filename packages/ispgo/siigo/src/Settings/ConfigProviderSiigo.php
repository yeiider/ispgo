<?php

namespace Ispgo\Siigo\Settings;

use App\Helpers\ConfigHelper;

class ConfigProviderSiigo
{
    public static function getEnabled(?int $scopeId = 0): ?bool
    {
        return self::getBoolValue('enabled', 'general', $scopeId);
    }

    public static function getEnvironment(?int $scopeId = 0): ?string
    {
        return self::getValue('environment', 'api', $scopeId);
    }

    public static function getBaseUrl(?int $scopeId = 0): ?string
    {
        return self::getValue('base_url', 'api', $scopeId);
    }

    public static function getUsername(?int $scopeId = 0): ?string
    {
        return self::getValue('username', 'api', $scopeId);
    }

    public static function getAccessKey(?int $scopeId = 0): ?string
    {
        return self::getValue('access_key', 'api', $scopeId);
    }

    public static function getPartnerId(?int $scopeId = 0): ?string
    {
        return self::getValue('partner_id', 'api', $scopeId);
    }

    public static function getSyncCustomer(?int $scopeId = 0): ?bool
    {
        return self::getBoolValue('sync_customer', 'general', $scopeId);
    }

    public static function getSyncInvoice(?int $scopeId = 0): ?bool
    {
        return self::getBoolValue('sync_invoice', 'invoices', $scopeId);
    }

    public static function getSyncInvoiceTrigger(?int $scopeId = 0): ?string
    {
        return self::getValue('sync_invoice_trigger', 'invoices', $scopeId);
    }

    public static function getSyncCustomersTrigger(?int $scopeId = 0): ?string
    {
        return self::getValue('sync_customers_trigger', 'general', $scopeId);
    }

    public static function getDocumentId(?int $scopeId = 0): ?int
    {
        $val = self::getValue('document_id', 'invoices', $scopeId);
        return $val !== null ? (int)$val : null;
    }

    public static function getPaymentId(?int $scopeId = 0): ?int
    {
        $val = self::getValue('payment_id', 'invoices', $scopeId);
        return $val !== null ? (int)$val : null;
    }

    public static function getProductCode(?int $scopeId = 0): ?string
    {
        return self::getValue('product_code', 'invoices', $scopeId);
    }

    public static function getTaxId(?int $scopeId = 0): ?int
    {
        $val = self::getValue('tax_id', 'invoices', $scopeId);
        return $val !== null ? (int)$val : null;
    }

    public static function getVoucherDocumentId(?int $scopeId = 0): ?int
    {
        $val = self::getValue('voucher_document_id', 'vouchers', $scopeId);
        return $val !== null ? (int)$val : null;
    }

    public static function getVoucherPaymentId(?int $scopeId = 0): ?int
    {
        $val = self::getValue('voucher_payment_id', 'vouchers', $scopeId);
        return $val !== null ? (int)$val : null;
    }

    private static function getMethodSettingKey(string $method): string
    {
        return match ($method) {
            'cash', 'efectivo' => 'voucher_payment_id_efectivo',
            'transfer', 'transferencia' => 'voucher_payment_id_transferencia',
            'card', 'tarjeta' => 'voucher_payment_id_tarjeta',
            'online', 'pago_online' => 'voucher_payment_id_pago_online',
            default => 'voucher_payment_id',
        };
    }

    /**
     * Get structured payment options array for a method.
     * Returns array of items: [ ['label' => 'Bancolombia 790', 'id' => 5647], ... ]
     */
    public static function getVoucherPaymentOptions(string $method, ?int $scopeId = 0): array
    {
        $settingKey = self::getMethodSettingKey($method);
        $raw = self::getValue($settingKey, 'vouchers', $scopeId);

        // Fallback to legacy voucher_payment_id or payment_id if method-specific setting is empty
        if (empty($raw)) {
            $fallback = self::getVoucherPaymentId($scopeId) ?: self::getPaymentId($scopeId) ?: 2719;
            return [['label' => 'Por defecto', 'id' => (int) $fallback]];
        }

        // Check if raw is JSON string mapping e.g. {"Bancolombia 790": 5647, "BBVA 450": 5648}
        if (is_string($raw) && str_starts_with(trim($raw), '{')) {
            $decoded = json_decode($raw, true);
            if (is_array($decoded)) {
                $result = [];
                foreach ($decoded as $label => $id) {
                    $result[] = [
                        'label' => (string) $label,
                        'id' => (int) $id,
                    ];
                }
                if (!empty($result)) {
                    return $result;
                }
            }
        }

        // Plain numeric string or integer ID
        return [['label' => 'Por defecto', 'id' => (int) $raw]];
    }

    /**
     * Get default voucher payment ID for a method
     */
    public static function getVoucherPaymentIdForMethod(string $method, ?int $scopeId = 0): int
    {
        $options = self::getVoucherPaymentOptions($method, $scopeId);
        return $options[0]['id'] ?? 2719;
    }

    public static function getVoucherAccountDebit(?int $scopeId = 0): ?string
    {
        return self::getValue('voucher_account_debit', 'vouchers', $scopeId);
    }

    public static function getVoucherAccountCredit(?int $scopeId = 0): ?string
    {
        return self::getValue('voucher_account_credit', 'vouchers', $scopeId);
    }

    public static function getCreditNoteDocumentId(?int $scopeId = 0): ?int
    {
        $val = self::getValue('credit_note_document_id', 'invoices', $scopeId);
        return $val !== null ? (int)$val : null;
    }

    public static function getSellerId(?int $scopeId = 0): ?int
    {
        $val = self::getValue('seller_id', 'others', $scopeId);
        return $val !== null ? (int)$val : null;
    }

    public static function getCostCenter(?int $scopeId = 0): ?int
    {
        $val = self::getValue('cost_center', 'others', $scopeId);
        return $val !== null ? (int)$val : null;
    }

    public static function getDefaultCityCode(?int $scopeId = 0): string
    {
        return self::getValue('default_city_code', 'other', $scopeId) ?: '11001';
    }

    private static function getValue(string $field, string $group = 'general', ?int $scopeId = 0): ?string
    {
        $scopeId = $scopeId ?? 0;

        // 1. Check for specific router/zone scope_id
        $val = ConfigHelper::getConfigValue("siigo/$group/$field", $scopeId);

        // 2. If null or empty string and scopeId > 0, fallback to Default Config (scope_id = 0)
        if (($val === null || $val === '') && $scopeId !== 0) {
            $val = ConfigHelper::getConfigValue("siigo/$group/$field", 0);
        }

        // 3. Fallback for backwards compatibility if saved under siigo/general/
        if (($val === null || $val === '') && $group !== 'general') {
            $val = ConfigHelper::getConfigValue("siigo/general/$field", $scopeId);
            if (($val === null || $val === '') && $scopeId !== 0) {
                $val = ConfigHelper::getConfigValue("siigo/general/$field", 0);
            }
        }

        return ($val !== null && $val !== '') ? $val : null;
    }

    private static function getBoolValue(string $field, string $group = 'general', ?int $scopeId = 0): ?bool
    {
        $val = self::getValue($field, $group, $scopeId);
        if ($val === null) {
            return null;
        }
        return filter_var($val, FILTER_VALIDATE_BOOLEAN);
    }
}
