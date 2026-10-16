<?php

namespace App\Settings\Oltgo;

use App\Helpers\ConfigHelper;
use Throwable;

/**
 * Valores de OLTGO: primero lo guardado en Configuración, luego el .env.
 */
class ProviderOltgo
{
    /**
     * ¿La zona usa OLTGO? Primero la configuración del router; si no la tiene,
     * la de por defecto. Sin nada guardado, OLTGO está activo.
     */
    public static function enabled(?int $routerId = null): bool
    {
        $value = self::get('enabled', $routerId);

        return $value === null ? true : (bool) $value;
    }

    public static function url(): string
    {
        return rtrim(self::get('url') ?: (string) config('oltgo.url'), '/');
    }

    public static function timeout(): int
    {
        return (int) (self::get('timeout') ?: config('oltgo.timeout', 20));
    }

    private static function get(string $key, ?int $routerId = null): ?string
    {
        $scope = (int) ($routerId ?? 0);
        try {
            $value = ConfigHelper::getConfigValue(SettingOltgo::GENERAL_PATH . $key, $scope);
            if (($value === null || $value === '') && $scope !== 0) {
                $value = ConfigHelper::getConfigValue(SettingOltgo::GENERAL_PATH . $key, 0);
            }
        } catch (Throwable) {
            return null;
        }

        return $value === '' ? null : $value;
    }
}
