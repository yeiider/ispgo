<?php

namespace Ispgo\Mikrotik\Settings;

use App\Helpers\ConfigHelper;

/**
 * Proveedor de configuración para el módulo Mikrotik
 * Obtiene valores de configuración usando el router_id como scope_id
 */
class MikrotikConfigProvider
{
    private const CONFIG_PREFIX = 'mikrotik/';

    /**
     * Obtener un valor de configuración para un router específico
     */
    public static function getValue(string $key, int $routerId = 0, ?string $default = null): ?string
    {
        $value = ConfigHelper::getConfigValue(self::CONFIG_PREFIX . $key, $routerId);
        return $value ?? $default;
    }

    /**
     * Verificar si el módulo está habilitado
     */
    public static function isEnabled(int $routerId = 0): bool
    {
        return self::getValue('general/enabled', $routerId, '0') === '1';
    }

    /**
     * Obtener la URL base del microservicio
     * Nota: Usa host.docker.internal para acceder desde Docker al host
     */
    public static function getApiBaseUrl(int $routerId = 0): string
    {
        return self::getValue('general/api_base_url', $routerId, 'http://host.docker.internal:8000/api/v1');
    }

    /**
     * Obtener el timeout de la API
     */
    public static function getApiTimeout(int $routerId = 0): int
    {
        return (int) self::getValue('general/api_timeout', $routerId, '30');
    }

    /**
     * Obtener las credenciales de conexión al router
     */
    public static function getRouterCredentials(int $routerId = 0): array
    {
        return [
            'host' => self::getValue('router_connection/host', $routerId, '192.168.88.1'),
            'port' => (int) self::getValue('router_connection/port', $routerId, '8728'),
            'username' => self::getValue('router_connection/username', $routerId, 'admin'),
            'password' => self::getValue('router_connection/password', $routerId, ''),
            'use_ssl' => self::getValue('router_connection/use_ssl', $routerId, '0') === '1',
        ];
    }

    /**
     * Verificar si DHCP binding está habilitado
     */
    public static function isDhcpEnabled(int $routerId = 0): bool
    {
        return self::getValue('dhcp/dhcp_enabled', $routerId, '1') === '1';
    }

    /**
     * Obtener el nombre del servidor DHCP
     */
    public static function getDhcpServer(int $routerId = 0): string
    {
        return self::getValue('dhcp/dhcp_server', $routerId, 'dhcp1');
    }

    /**
     * Verificar si auto-bind está habilitado
     */
    public static function isAutoBindEnabled(int $routerId = 0): bool
    {
        return self::getValue('dhcp/auto_bind_on_provision', $routerId, '1') === '1';
    }

    /**
     * Verificar si Simple Queue está habilitado
     */
    public static function isQueueEnabled(int $routerId = 0): bool
    {
        return self::getValue('simple_queue/queue_enabled', $routerId, '1') === '1';
    }

    /**
     * Obtener el prefijo para nombres de queue
     */
    public static function getQueueNamePrefix(int $routerId = 0): string
    {
        return self::getValue('simple_queue/queue_name_prefix', $routerId, '');
    }

    /**
     * Verificar si burst está habilitado
     */
    public static function isBurstEnabled(int $routerId = 0): bool
    {
        return self::getValue('simple_queue/burst_enabled', $routerId, '0') === '1';
    }

    /**
     * Obtener configuración de burst
     */
    public static function getBurstConfig(int $routerId = 0): array
    {
        return [
            'limit_percentage' => (int) self::getValue('simple_queue/burst_limit_percentage', $routerId, '150'),
            'threshold_percentage' => (int) self::getValue('simple_queue/burst_threshold_percentage', $routerId, '75'),
            'time' => self::getValue('simple_queue/burst_time', $routerId, '8s/8s'),
        ];
    }

    /**
     * Obtener acción al suspender servicio
     */
    public static function getSuspendAction(int $routerId = 0): string
    {
        return self::getValue('service_actions/suspend_action', $routerId, 'disable_queue');
    }

    /**
     * Obtener límites de velocidad para servicio suspendido
     */
    public static function getSuspendLimits(int $routerId = 0): array
    {
        return [
            'upload' => self::getValue('service_actions/suspend_limit_upload', $routerId, '64k'),
            'download' => self::getValue('service_actions/suspend_limit_download', $routerId, '128k'),
        ];
    }

    /**
     * Obtener acción al activar servicio
     */
    public static function getActivateAction(int $routerId = 0): string
    {
        return self::getValue('service_actions/activate_action', $routerId, 'enable_queue');
    }

    /**
     * Verificar si modo debug está habilitado
     */
    public static function isDebugMode(int $routerId = 0): bool
    {
        return self::getValue('advanced/debug_mode', $routerId, '0') === '1';
    }

    /**
     * Obtener configuración de reintentos
     */
    public static function getRetryConfig(int $routerId = 0): array
    {
        return [
            'attempts' => (int) self::getValue('advanced/retry_attempts', $routerId, '3'),
            'delay' => (int) self::getValue('advanced/retry_delay', $routerId, '5'),
        ];
    }

    /**
     * Obtener template de comentario para queues
     */
    public static function getQueueCommentTemplate(int $routerId = 0): string
    {
        return self::getValue('advanced/queue_comment_template', $routerId, 'Servicio #{service_id}');
    }
}
