<?php

namespace App\Settings;

use App\Helpers\ConfigHelper;

class EmailConfigProvider
{
    const PATH = "notifications/email_settings/";


    public static function isEnabled(): bool
    {
        return self::getValue("enabled");
    }

    public static function getHost(): string
    {
        return self::getValue("host");
    }

    public static function getPort(): string
    {
        return self::getValue("port");
    }

    public static function getUsername(): string
    {
        return self::getValue("username");
    }

    public static function getPassword(): string
    {
        return self::getValue("password");
    }

    public static function getSecurity(): string{
        return self::getValue("security");
    }

    public static function getValue(string $field): ?string
    {
        return ConfigHelper::getConfigValue(self::PATH . $field);
    }
}
