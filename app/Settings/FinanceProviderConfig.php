<?php

namespace App\Settings;

use App\Helpers\ConfigHelper;

class FinanceProviderConfig
{
    const CASH_REGISTER_PATH = "finance/cash_register/";

    public static function isAutoCloseEnabled(): bool
    {
        return (bool) ConfigHelper::getConfigValue(self::CASH_REGISTER_PATH . 'auto_close_enabled');
    }

    public static function getAutoCloseTime(): string
    {
        return ConfigHelper::getConfigValue(self::CASH_REGISTER_PATH . 'auto_close_time') ?? '23:59';
    }
}
