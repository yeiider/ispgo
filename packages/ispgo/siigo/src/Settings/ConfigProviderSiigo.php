<?php

namespace Ispgo\Siigo\Settings;

use App\Helpers\ConfigHelper;

class ConfigProviderSiigo
{
    public static function getEnabled(): ?bool
    {
        return self::getValue('enabled');
    }

    public static function getEnvironment(): ?string
    {
        return self::getValue('environment');
    }

    public static function getBaseUrl(): ?string
    {
        return self::getValue('base_url');
    }

    public static function getUsername(): ?string
    {
        return self::getValue('username');
    }

    public static function getAccessKey(): ?string
    {
        return self::getValue('access_key');
    }

    public static function getPartnerId(): ?string
    {
        return self::getValue('partner_id');
    }

    public static function getSyncCustomer(): ?bool
    {
        return self::getValue('sync_customer');
    }

    public static function getSyncInvoice(): ?bool
    {
        return self::getValue('sync_invoice');
    }

    public static function getSyncInvoiceTrigger(): ?string
    {
        return self::getValue('sync_invoice_trigger');
    }

    public static function getSyncCustomersTrigger(): ?string
    {
        return self::getValue('sync_customers_trigger');
    }

    private static function getValue($field): ?string
    {
        return ConfigHelper::getConfigValue("siigo/general/$field");
    }
}
