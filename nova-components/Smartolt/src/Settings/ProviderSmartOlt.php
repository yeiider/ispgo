<?php

namespace Ispgo\Smartolt\Settings;

use App\Helpers\ConfigHelper;

/**
 * Configuración de SmartOLT por zona (router). Cada valor se busca primero en
 * la configuración del router (scope_id = router_id) y, si no está definido
 * ahí, en la configuración por defecto (scope_id = 0). Sin router = default.
 */
class ProviderSmartOlt
{
    const GENERAL_PATH = "smartolt/general/";
    const ACTIVATION_PATH = "smartolt/activation/";

    public static function value(string $path, ?int $routerId = null): ?string
    {
        $scope = (int) ($routerId ?? 0);
        $val = ConfigHelper::getConfigValue($path, $scope);
        if (($val === null || $val === '') && $scope !== 0) {
            $val = ConfigHelper::getConfigValue($path, 0);
        }

        return $val === '' ? null : $val;
    }

    public static function getEnabled(?int $routerId = null): ?bool
    {
        return (bool) self::value(self::GENERAL_PATH . 'enabled', $routerId);
    }

    public static function getToken(?int $routerId = null): ?string
    {
        return self::value(self::GENERAL_PATH . 'token', $routerId);
    }

    public static function getUrl(?int $routerId = null): ?string
    {
        return self::value(self::GENERAL_PATH . 'url', $routerId);
    }

    public static function getEnabledCustomer(?int $routerId = null): ?bool
    {
        return (bool) self::value(self::GENERAL_PATH . 'customer_enabled', $routerId);
    }

    public static function getDefaultVlan(?int $routerId = null): int
    {
        return (int) (self::value(self::ACTIVATION_PATH . 'default_vlan', $routerId) ?? 700);
    }

    public static function getTr069Profile(?int $routerId = null): string
    {
        return self::value(self::ACTIVATION_PATH . 'tr069_profile', $routerId) ?? 'SmartOLT';
    }

    public static function getWanConfigurationMethod(?int $routerId = null): string
    {
        return self::value(self::ACTIVATION_PATH . 'wan_configuration_method', $routerId) ?? 'TR069';
    }

    public static function getIpProtocol(?int $routerId = null): string
    {
        return self::value(self::ACTIVATION_PATH . 'ip_protocol', $routerId) ?? 'ipv4ipv6';
    }

    public static function getIpv6AddressMode(?int $routerId = null): string
    {
        return self::value(self::ACTIVATION_PATH . 'ipv6_address_mode', $routerId) ?? 'Auto';
    }

    public static function getIpv6PrefixDelegationMode(?int $routerId = null): string
    {
        return self::value(self::ACTIVATION_PATH . 'ipv6_prefix_delegation_mode', $routerId) ?? 'DHCPv6-PD';
    }
}
