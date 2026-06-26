<?php

namespace Ispgo\Smartolt\Settings;

class SettingSmartolt
{

    public static function getGeneralSettings(): array
    {
        return [
            "setting" => [
                "label" => "General Information",
                "code" => "general"
            ],
            "enabled" => [
                "field" => "boolean-field",
                "label" => "Enabled",
                "placeholder" => "Enabled",
            ],
            "url" => [
                "field" => "text-field",
                "label" => "Url",
                "placeholder" => "https://globalraices.smartolt.com/",
            ],
            "token" => [
                "field" => "text-field",
                "label" => "Token",
                "placeholder" => "xxxxxxxxxxxxxxxxxxxxxxx",
            ],
            "customer_enabled" => [
                "field" => "boolean-field",
                "label" => "Habilitar y deshabilitar clientes",
                "placeholder" => "Enabled",
            ],
        ];
    }

    public static function getActivationSettings(): array
    {
        return [
            "setting" => [
                "label" => "Configuración de Activación ONU",
                "code" => "activation"
            ],
            "default_vlan" => [
                "field" => "number-field",
                "label" => "VLAN por defecto",
                "placeholder" => "700",
            ],
            "tr069_profile" => [
                "field" => "text-field",
                "label" => "Perfil TR069",
                "placeholder" => "SmartOLT",
            ],
            "wan_configuration_method" => [
                "field" => "text-field",
                "label" => "Método de configuración WAN",
                "placeholder" => "TR069",
            ],
            "ip_protocol" => [
                "field" => "text-field",
                "label" => "Protocolo IP",
                "placeholder" => "ipv4ipv6",
            ],
            "ipv6_address_mode" => [
                "field" => "text-field",
                "label" => "Modo de dirección IPv6",
                "placeholder" => "Auto",
            ],
            "ipv6_prefix_delegation_mode" => [
                "field" => "text-field",
                "label" => "Modo de delegación de prefijo IPv6",
                "placeholder" => "DHCPv6-PD",
            ],
        ];
    }
}
