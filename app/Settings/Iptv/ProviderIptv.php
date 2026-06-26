<?php

namespace App\Settings\Iptv;

use App\Helpers\ConfigHelper;

class ProviderIptv
{
    const GENERAL_PATH = "iptv/general/";
    const ACTIVATION_PATH = "iptv/activation/";

    public static function getEnabled(): bool
    {
        return (bool) ConfigHelper::getConfigValue(self::GENERAL_PATH . 'enabled');
    }

    public static function getUrl(): ?string
    {
        return ConfigHelper::getConfigValue(self::GENERAL_PATH . 'url');
    }

    public static function getAccessCode(): ?string
    {
        return ConfigHelper::getConfigValue(self::GENERAL_PATH . 'access_code');
    }

    public static function getApiKey(): ?string
    {
        return ConfigHelper::getConfigValue(self::GENERAL_PATH . 'api_key');
    }

    public static function getDefaultMaxConnections(): int
    {
        $val = ConfigHelper::getConfigValue(self::ACTIVATION_PATH . 'default_max_connections');
        return is_numeric($val) ? (int) $val : 1;
    }

    public static function getDefaultMemberId(): ?int
    {
        $val = ConfigHelper::getConfigValue(self::ACTIVATION_PATH . 'default_member_id');
        return is_numeric($val) ? (int) $val : null;
    }

    public static function getDefaultBouquets(): string
    {
        return ConfigHelper::getConfigValue(self::ACTIVATION_PATH . 'default_bouquets') ?? '';
    }
}
