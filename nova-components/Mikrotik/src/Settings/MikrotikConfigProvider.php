<?php

namespace Ispgo\Mikrotik\Settings;

use App\Helpers\ConfigHelper;
use function Symfony\Component\String\b;

class MikrotikConfigProvider
{
    const GENERAL_PATH = "mikrotik/general/";
    const PPP_PATH = "mikrotik/ppp/";
    const SIMPLE_QUEUE_PATH = "mikrotik/simple_queue/";
    const DHCP_PATH = "mikrotik/dhcp/";
    const IP_POOL_PATH = "mikrotik/ip_pool/";
    const STATIC_IP_PATH = "mikrotik/static_ip/";
    const QOS_PATH = "mikrotik/qos/";

    // General MikroTik settings
    public static function getEnabled(): ?bool
    {
        return (bool)ConfigHelper::getConfigValue(self::GENERAL_PATH . 'enabled');
    }

    public static function getHost(): ?string
    {
        return ConfigHelper::getConfigValue(self::GENERAL_PATH . 'host');
    }

    public static function getPort(): ?int
    {
        return (int)ConfigHelper::getConfigValue(self::GENERAL_PATH . 'port');
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
    public static function getPppEnabled(): ?bool
    {
        return (bool)ConfigHelper::getConfigValue(self::PPP_PATH . 'ppp_enabled');
    }

    public static function getServiceType(): ?string
    {
        return ConfigHelper::getConfigValue(self::PPP_PATH . 'service_type');
    }

    public static function getIpPoolEnabled(): bool
    {
        return (bool)ConfigHelper::getConfigValue(self::PPP_PATH . 'ip_pool_enabled');
    }

    public static function getClientIdentifier(): string
    {
        return (string)ConfigHelper::getConfigValue(self::PPP_PATH . 'client_identifier');
    }

    public static function getStaticIpEnabled(): string
    {
        return (string)ConfigHelper::getConfigValue(self::PPP_PATH . 'static_ip_enabled');
    }

    public static function getPasswordPPPSecret():string
    {
        return (string)ConfigHelper::getConfigValue(self::PPP_PATH . 'password_ppp_secret');

    }

    // Simple Queue settings
    public static function getSimpleQueueEnabled(): bool
    {
        return (bool)ConfigHelper::getConfigValue(self::SIMPLE_QUEUE_PATH . 'simple_queue_enabled');
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
        return ConfigHelper::getConfigValue(self::DHCP_PATH . 'dhcp_enabled');
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


    // Static IP settings
    public static function getStaticIPAddress(string $identifier): ?string
    {
        return ConfigHelper::getConfigValue(self::STATIC_IP_PATH . $identifier . '/ip_address');
    }

    // QoS settings
    public static function getQoSEnabled(): ?string
    {
        return ConfigHelper::getConfigValue(self::QOS_PATH . 'qos_enabled');
    }

    public static function getQoSPriority(): ?string
    {
        return ConfigHelper::getConfigValue(self::QOS_PATH . 'priority');
    }

    public static function getQoSMaxLimit(): ?string
    {
        return ConfigHelper::getConfigValue(self::QOS_PATH . 'max_limit');
    }



}
