<?php

namespace App\Settings;

use App\Helpers\ConfigHelper;

/**
 * OnePay settings accessor and definition holder.
 * All properties are prefixed with onepay_ as required.
 */
class OnePaySettings
{
    // Public properties as requested by the spec
    public bool $onepay_enabled = false;
    public string $onepay_base_url = '';
    public string $onepay_api_token = '';
    public ?int $onepay_auto_create_day = null; // 1-31
    public ?int $onepay_auto_remind_day = null; // 1-31

    // Internal path for settings stored via Settings Manager
    public const PATH = 'onepay/general/';

    public static function enabled(): bool
    {
        return (bool) ConfigHelper::getConfigValue(self::PATH . 'onepay_enabled');
    }

    public static function baseUrl(): ?string
    {
        return ConfigHelper::getConfigValue(self::PATH . 'onepay_base_url');
    }

    public static function apiToken(): ?string
    {
        return ConfigHelper::getConfigValue(self::PATH . 'onepay_api_token');
    }

    public static function autoCreateDay(): ?int
    {
        $day = ConfigHelper::getConfigValue(self::PATH . 'onepay_auto_create_day');
        return is_numeric($day) ? (int) $day : null;
    }

    public static function autoRemindDay(): ?int
    {
        $day = ConfigHelper::getConfigValue(self::PATH . 'onepay_auto_remind_day');
        return is_numeric($day) ? (int) $day : null;
    }
}
