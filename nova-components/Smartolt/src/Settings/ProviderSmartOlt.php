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

    public static function getToken(): ?string
    {
        return ConfigHelper::getConfigValue(self::GENERAL_PATH . 'token');
    }

    public static function getUrl(): ?string
    {
        return ConfigHelper::getConfigValue(self::GENERAL_PATH . 'url');
    }
    public static function getEnabledCustomer(): ?bool
    {
        return (bool)ConfigHelper::getConfigValue(self::GENERAL_PATH . 'customer_enabled');
    }

    const ACTIVATION_PATH = "smartolt/activation/";

    public static function getDefaultVlan(): int
    {
        return (int)(ConfigHelper::getConfigValue(self::ACTIVATION_PATH . 'default_vlan') ?? 700);
    }

    public static function getTr069Profile(): string
    {
        return ConfigHelper::getConfigValue(self::ACTIVATION_PATH . 'tr069_profile') ?? 'SmartOLT';
    }

    public static function getWanConfigurationMethod(): string
    {
        return ConfigHelper::getConfigValue(self::ACTIVATION_PATH . 'wan_configuration_method') ?? 'TR069';
    }

    public static function getIpProtocol(): string
    {
        return ConfigHelper::getConfigValue(self::ACTIVATION_PATH . 'ip_protocol') ?? 'ipv4ipv6';
    }

    public static function getIpv6AddressMode(): string
    {
        return ConfigHelper::getConfigValue(self::ACTIVATION_PATH . 'ipv6_address_mode') ?? 'Auto';
    }

    public static function getIpv6PrefixDelegationMode(): string
    {
        return ConfigHelper::getConfigValue(self::ACTIVATION_PATH . 'ipv6_prefix_delegation_mode') ?? 'DHCPv6-PD';
    }

}
