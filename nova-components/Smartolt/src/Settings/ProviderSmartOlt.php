<?php

namespace Ispgo\Smartolt\Settings;

use App\Helpers\ConfigHelper;

class ProviderSmartOlt
{
    const GENERAL_PATH = "smartolt/general/";

    public static function getEnabled(): ?bool
    {
        return (bool)ConfigHelper::getConfigValue(self::GENERAL_PATH . 'enabled');
    }

    public static function getToken(): ?bool
    {
        return (bool)ConfigHelper::getConfigValue(self::GENERAL_PATH . 'token');
    }

    public static function getUrl(): ?bool
    {
        return (bool)ConfigHelper::getConfigValue(self::GENERAL_PATH . 'url');
    }
    public static function getEnabledCustomer(): ?bool
    {
        return (bool)ConfigHelper::getConfigValue(self::GENERAL_PATH . 'customer_enabled');
    }

}
