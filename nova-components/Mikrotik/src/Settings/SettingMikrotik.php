<?php

namespace Ispgo\Mikrotik\Settings;

use Ispgo\Mikrotik\Settings\Config\Sources\QueueType;
use Ispgo\Mikrotik\Settings\Config\Sources\ServicesType;
use Laravel\Nova\Menu\MenuSection;

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
                "field" => "text-field",
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
            "ppp_enabled" => [
                "field" => "boolean-field",
                "label" => "Habilitar PPP",
                "placeholder" => "Habilitar PPP",
            ],
            "service_type" => [
                "field" => "select-field",
                "label" => "Service Type",
                "placeholder" => "default",
                "options" => ServicesType::class
            ],
            "ip_pool_enabled" => [
                "field" => "boolean-field",
                "label" => "Habilitar IP Pool",
                "placeholder" => "Habilitar IP Pool",
            ],

            "static_ip_enabled" => [
                "field" => "boolean-field",
                "label" => "Habilitar IP Estática",
                "placeholder" => "Habilitar IP Estática",
            ],
            "client_identifier" => [
                "field" => "text-field",
                "label" => "Identificador de Cliente (MAC o PPP Secret)",
                "placeholder" => "00:11:22:33:44:55 o pppoeuser1",
            ],
            "password_ppp_secret" => [
                "field" => "text-field",
                "label" => "Password PPP Secret (PPP Secret)",
                "placeholder" => "Password",
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
            "simple_queue_enabled" => [
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






    public static function getQoSSettings(): array
    {
        return [
            "setting" => [
                "label" => "QoS Configuration",
                "code" => "qos"
            ],
            "qos_enabled" => [
                "field" => "boolean-field",
                "label" => "Habilitar QoS",
                "placeholder" => "Habilitar QoS",
            ],
            "priority" => [
                "field" => "number-field",
                "label" => "Prioridad del Tráfico",
                "placeholder" => "1-8",
            ],
            "max_limit" => [
                "field" => "text-field",
                "label" => "Límite Máximo de Ancho de Banda",
                "placeholder" => "50M/50M",
            ],
        ];
    }

    public static function getIPv6Settings(): array
    {
        return [
            "setting" => [
                "label" => "IPv6 Configuration",
                "code" => "ipv6"
            ],
            "ipv_6_enabled" => [
                "field" => "boolean-field",
                "label" => "Habilitar IPv6",
                "placeholder" => "Habilitar IPv6",
            ],
            "ipv6_pool" => [
                "field" => "text-field",
                "label" => "Pool IPv6",
                "placeholder" => "ipv6-pool1",
            ],
            "prefix_length" => [
                "field" => "number-field",
                "label" => "Longitud del Prefijo",
                "placeholder" => "64",
            ],
        ];
    }

    public static function getMonitoringSettings(): array
    {
        return [
            "setting" => [
                "label" => "Monitoring and Notifications",
                "code" => "monitoring"
            ],
            "monitory_enabled" => [
                "field" => "boolean-field",
                "label" => "Habilitar Monitoreo",
                "placeholder" => "Habilitar Monitoreo",
            ],
            "notification_email" => [
                "field" => "text-field",
                "label" => "Correo Electrónico para Notificaciones",
                "placeholder" => "admin@isp.com",
            ],
            "alert_threshold" => [
                "field" => "number-field",
                "label" => "Umbral de Alerta (Uso de CPU o Ancho de Banda)",
                "placeholder" => "80 (%)",
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
            "dhcp_enabled" => [
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
