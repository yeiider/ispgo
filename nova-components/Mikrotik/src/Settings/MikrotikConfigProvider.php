<?php

namespace Ispgo\Mikrotik\Settings;

use App\Helpers\ConfigHelper;

class MikrotikConfigProvider
{
    const GENERAL_PATH = "mikrotik/general/";
    const PPP_PATH = "mikrotik/ppp/";
    const SIMPLE_QUEUE_PATH = "mikrotik/simple_queue/";
    const DHCP_PATH = "mikrotik/dhcp/";

    // General MikroTik settings
    public static function getEnabled(): ?string
    {
        return ConfigHelper::getConfigValue(self::GENERAL_PATH . 'enabled');
    }

    public static function getHost(): ?string
    {
        return ConfigHelper::getConfigValue(self::GENERAL_PATH . 'host');
    }

    public static function getPort(): ?string
    {
        return ConfigHelper::getConfigValue(self::GENERAL_PATH . 'port');
    }

    public static function getUsername(): ?string
    {
        return ConfigHelper::getConfigValue(self::GENERAL_PATH . 'username');
    }

    public static function getPassword(): ?string
    {
        return ConfigHelper::getConfigValue(self::GENERAL_PATH . 'password');
    }

    public static function getSsl(): ?string
    {
        return ConfigHelper::getConfigValue(self::GENERAL_PATH . 'ssl');
    }

    public static function getTimeout(): ?string
    {
        return ConfigHelper::getConfigValue(self::GENERAL_PATH . 'timeout');
    }

    // PPP settings
    public static function getPppEnabled(): ?string
    {
        return ConfigHelper::getConfigValue(self::PPP_PATH . 'enabled');
    }

    public static function getPppDefaultProfile(): ?string
    {
        return ConfigHelper::getConfigValue(self::PPP_PATH . 'default_profile');
    }

    public static function getPppMaxSessions(): ?string
    {
        return ConfigHelper::getConfigValue(self::PPP_PATH . 'max_sessions');
    }

    // Simple Queue settings
    public static function getSimpleQueueEnabled(): ?string
    {
        return ConfigHelper::getConfigValue(self::SIMPLE_QUEUE_PATH . 'enabled');
    }

    public static function getSimpleQueueLimitUpload(): ?string
    {
        return ConfigHelper::getConfigValue(self::SIMPLE_QUEUE_PATH . 'default_limit_upload');
    }

    public static function getSimpleQueueLimitDownload(): ?string
    {
        return ConfigHelper::getConfigValue(self::SIMPLE_QUEUE_PATH . 'default_limit_download');
    }

    public static function getSimpleQueueType(): ?string
    {
        return ConfigHelper::getConfigValue(self::SIMPLE_QUEUE_PATH . 'queue_type');
    }

    // DHCP settings
    public static function getDhcpEnabled(): ?string
    {
        return ConfigHelper::getConfigValue(self::DHCP_PATH . 'enabled');
    }

    public static function getDhcpPool(): ?string
    {
        return ConfigHelper::getConfigValue(self::DHCP_PATH . 'dhcp_pool');
    }

    public static function getDhcpLeaseTime(): ?string
    {
        return ConfigHelper::getConfigValue(self::DHCP_PATH . 'lease_time');
    }

    public static function getDhcpDnsServers(): ?string
    {
        return ConfigHelper::getConfigValue(self::DHCP_PATH . 'dns_servers');
    }
}
