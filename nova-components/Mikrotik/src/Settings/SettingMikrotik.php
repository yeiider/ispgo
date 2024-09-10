<?php

namespace Ispgo\Mikrotik\Settings;

use Ispgo\Mikrotik\Settings\Config\Sources\QueueType;

class SettingMikrotik
{
    // Configuración General
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
            "host" => [
                "field" => "text-field",
                "label" => "Host",
                "placeholder" => "192.168.88.1",
            ],
            "port" => [
                "field" => "number-field",
                "label" => "Puerto API",
                "placeholder" => "8728",
            ],
            "username" => [
                "field" => "text-field",
                "label" => "Usuario API",
                "placeholder" => "admin",
            ],
            "password" => [
                "field" => "password-field",
                "label" => "Contraseña API",
                "placeholder" => "********",
            ],
            "ssl" => [
                "field" => "boolean-field",
                "label" => "Usar SSL",
                "placeholder" => "Usar SSL",
            ],
            "timeout" => [
                "field" => "number-field",
                "label" => "Tiempo de Espera (segundos)",
                "placeholder" => "5",
            ],
        ];
    }

    // Configuración PPP
    public static function getPPPSettings(): array
    {
        return [
            "setting" => [
                "label" => "PPP Configuration",
                "code" => "ppp"
            ],
            "enabled" => [
                "field" => "boolean-field",
                "label" => "Habilitar PPP",
                "placeholder" => "Habilitar PPP",
            ],
            "default_profile" => [
                "field" => "text-field",
                "label" => "Perfil PPP por Defecto",
                "placeholder" => "default",
            ],
            "max_sessions" => [
                "field" => "number-field",
                "label" => "Máximo de Sesiones",
                "placeholder" => "10",
            ],
        ];
    }

    // Configuración de Simple Queue
    public static function getSimpleQueueSettings(): array
    {
        return [
            "setting" => [
                "label" => "Simple Queue Configuration",
                "code" => "simple_queue"
            ],
            "enabled" => [
                "field" => "boolean-field",
                "label" => "Habilitar Simple Queue",
                "placeholder" => "Habilitar Simple Queue",
            ],
            "default_limit_upload" => [
                "field" => "text-field",
                "label" => "Límite de Subida por Defecto",
                "placeholder" => "10M",
            ],
            "default_limit_download" => [
                "field" => "text-field",
                "label" => "Límite de Bajada por Defecto",
                "placeholder" => "10M",
            ],
            "queue_type" => [
                "field" => "select-field",
                "label" => "Tipo de Cola",
                "placeholder" => "Seleccionar Tipo",
                "options" => QueueType::class
            ],
        ];
    }

    // Configuración DHCP
    public static function getDHCPSettings(): array
    {
        return [
            "setting" => [
                "label" => "DHCP Configuration",
                "code" => "dhcp"
            ],
            "enabled" => [
                "field" => "boolean-field",
                "label" => "Habilitar DHCP",
                "placeholder" => "Habilitar DHCP",
            ],
            "dhcp_pool" => [
                "field" => "text-field",
                "label" => "Pool DHCP",
                "placeholder" => "dhcp_pool1",
            ],
            "lease_time" => [
                "field" => "text-field",
                "label" => "Tiempo de Arrendamiento",
                "placeholder" => "1d",
            ],
            "dns_servers" => [
                "field" => "text-field",
                "label" => "Servidores DNS",
                "placeholder" => "8.8.8.8,8.8.4.4",
            ],
        ];
    }
}
