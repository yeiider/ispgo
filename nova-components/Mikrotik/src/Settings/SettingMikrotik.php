<?php

namespace Ispgo\Mikrotik\Settings;

/**
 * Configuración simplificada de Mikrotik para el nuevo flujo:
 * - Conexión con microservicio API
 * - Configuración por router (scope_id)
 * - Simple Queue + DHCP Binding
 */
class SettingMikrotik
{
    /**
     * Configuración General del Módulo
     * Define la URL del microservicio y opciones globales
     */
    public static function getGeneralSettings(): array
    {
        return [
            "setting" => [
                "label" => "Configuración General",
                "code" => "general"
            ],
            "enabled" => [
                "field" => "boolean-field",
                "label" => "Módulo Habilitado",
                "placeholder" => "Activar módulo Mikrotik",
                "description" => "Habilita o deshabilita el módulo de Mikrotik"
            ],
            "api_base_url" => [
                "field" => "text-field",
                "label" => "URL Base del Microservicio",
                "placeholder" => "http://localhost:8000/api/v1",
                "description" => "URL base del microservicio de Mikrotik (sin slash final)"
            ],
            "api_timeout" => [
                "field" => "number-field",
                "label" => "Timeout de API (segundos)",
                "placeholder" => "30",
                "description" => "Tiempo máximo de espera para las peticiones al microservicio"
            ],
        ];
    }

    /**
     * Configuración de Conexión al Router
     * Credenciales que se envían al microservicio para conectar con el router
     */
    public static function getRouterConnectionSettings(): array
    {
        return [
            "setting" => [
                "label" => "Conexión al Router",
                "code" => "router_connection"
            ],
            "host" => [
                "field" => "text-field",
                "label" => "IP del Router",
                "placeholder" => "192.168.88.1",
                "description" => "Dirección IP del router Mikrotik"
            ],
            "port" => [
                "field" => "number-field",
                "label" => "Puerto API",
                "placeholder" => "8728",
                "description" => "Puerto de la API de Mikrotik (8728 sin SSL, 8729 con SSL)"
            ],
            "username" => [
                "field" => "text-field",
                "label" => "Usuario",
                "placeholder" => "admin",
                "description" => "Usuario con permisos de API en el router"
            ],
            "password" => [
                "field" => "password-field",
                "label" => "Contraseña",
                "placeholder" => "********",
                "description" => "Contraseña del usuario API"
            ],
            "use_ssl" => [
                "field" => "boolean-field",
                "label" => "Usar SSL",
                "placeholder" => "Conexión segura",
                "description" => "Usar conexión SSL/TLS al router"
            ],
        ];
    }

    /**
     * Configuración de DHCP
     * Opciones para el manejo de DHCP leases
     */
    public static function getDhcpSettings(): array
    {
        return [
            "setting" => [
                "label" => "Configuración DHCP",
                "code" => "dhcp"
            ],
            "dhcp_enabled" => [
                "field" => "boolean-field",
                "label" => "Habilitar Binding DHCP",
                "placeholder" => "Activar binding de IP",
                "description" => "Habilita el amarre de IP a MAC address"
            ],
            "dhcp_server" => [
                "field" => "text-field",
                "label" => "Servidor DHCP",
                "placeholder" => "dhcp1",
                "description" => "Nombre del servidor DHCP en el router"
            ],
            "auto_bind_on_provision" => [
                "field" => "boolean-field",
                "label" => "Auto-bind en Provisión",
                "placeholder" => "Bind automático",
                "description" => "Crear binding automáticamente al provisionar servicio"
            ],
        ];
    }

    /**
     * Configuración de Simple Queue
     * Opciones para el control de ancho de banda
     */
    public static function getSimpleQueueSettings(): array
    {
        return [
            "setting" => [
                "label" => "Configuración Simple Queue",
                "code" => "simple_queue"
            ],
            "queue_enabled" => [
                "field" => "boolean-field",
                "label" => "Habilitar Simple Queue",
                "placeholder" => "Activar control de ancho de banda",
                "description" => "Habilita la creación de Simple Queues para control de velocidad"
            ],
            "queue_name_prefix" => [
                "field" => "text-field",
                "label" => "Prefijo de Nombre",
                "placeholder" => "SVC_",
                "description" => "Prefijo para los nombres de las queues (ej: SVC_123)"
            ],
            "burst_enabled" => [
                "field" => "boolean-field",
                "label" => "Habilitar Burst",
                "placeholder" => "Activar burst de velocidad",
                "description" => "Permite ráfagas de velocidad temporales"
            ],
            "burst_limit_percentage" => [
                "field" => "number-field",
                "label" => "Burst Limit (%)",
                "placeholder" => "150",
                "description" => "Porcentaje del límite máximo durante el burst"
            ],
            "burst_threshold_percentage" => [
                "field" => "number-field",
                "label" => "Burst Threshold (%)",
                "placeholder" => "75",
                "description" => "Porcentaje de uso para activar burst"
            ],
            "burst_time" => [
                "field" => "text-field",
                "label" => "Burst Time",
                "placeholder" => "8s/8s",
                "description" => "Tiempo de burst (upload/download)"
            ],
        ];
    }

    /**
     * Configuración de Acciones de Servicio
     * Comportamiento al cambiar estados del servicio
     */
    public static function getServiceActionsSettings(): array
    {
        return [
            "setting" => [
                "label" => "Acciones de Servicio",
                "code" => "service_actions"
            ],
            "suspend_action" => [
                "field" => "select-field",
                "label" => "Acción al Suspender",
                "placeholder" => "disable_queue",
                "description" => "Qué hacer cuando se suspende un servicio",
                "options" => [
                    ["value" => "disable_queue", "label" => "Deshabilitar Queue"],
                    ["value" => "limit_speed", "label" => "Limitar Velocidad"],
                    ["value" => "remove_queue", "label" => "Eliminar Queue"],
                ]
            ],
            "suspend_limit_upload" => [
                "field" => "text-field",
                "label" => "Velocidad Suspendido (Upload)",
                "placeholder" => "64k",
                "description" => "Velocidad de subida cuando el servicio está suspendido"
            ],
            "suspend_limit_download" => [
                "field" => "text-field",
                "label" => "Velocidad Suspendido (Download)",
                "placeholder" => "128k",
                "description" => "Velocidad de bajada cuando el servicio está suspendido"
            ],
            "activate_action" => [
                "field" => "select-field",
                "label" => "Acción al Activar",
                "placeholder" => "enable_queue",
                "description" => "Qué hacer cuando se activa un servicio",
                "options" => [
                    ["value" => "enable_queue", "label" => "Habilitar Queue"],
                    ["value" => "restore_speed", "label" => "Restaurar Velocidad"],
                    ["value" => "create_queue", "label" => "Crear Queue"],
                ]
            ],
        ];
    }

    /**
     * Configuración Avanzada
     * Opciones adicionales para casos especiales
     */
    public static function getAdvancedSettings(): array
    {
        return [
            "setting" => [
                "label" => "Configuración Avanzada",
                "code" => "advanced"
            ],
            "debug_mode" => [
                "field" => "boolean-field",
                "label" => "Modo Debug",
                "placeholder" => "Activar logs detallados",
                "description" => "Registra información detallada en los logs"
            ],
            "retry_attempts" => [
                "field" => "number-field",
                "label" => "Intentos de Reintento",
                "placeholder" => "3",
                "description" => "Número de reintentos en caso de fallo"
            ],
            "retry_delay" => [
                "field" => "number-field",
                "label" => "Delay entre Reintentos (seg)",
                "placeholder" => "5",
                "description" => "Segundos de espera entre reintentos"
            ],
            "queue_comment_template" => [
                "field" => "text-field",
                "label" => "Template de Comentario",
                "placeholder" => "Cliente: {customer_name} - Plan: {plan_name}",
                "description" => "Plantilla para el comentario del queue"
            ],
        ];
    }
}
