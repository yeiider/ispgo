<?php

namespace App\Support;

use Ispgo\Smartolt\Settings\ProviderSmartOlt;

/**
 * Punto único para saber si SmartOLT está activo para una zona (router).
 * Cada zona usa su propia configuración y, si no la tiene, la de por defecto.
 * Si SmartOLT está apagado para esa zona, sus acciones responden con un
 * mensaje claro y la lectura de la ONU la hace OLTGO.
 */
class SmartOltGate
{
    public const DISABLED_MESSAGE = 'SmartOLT está deshabilitado para esta zona en Configuración. Esta acción requiere SmartOLT; OLTGO por ahora solo lee datos de las OLTs.';

    public static function enabled(?int $routerId = null): bool
    {
        try {
            return ProviderSmartOlt::getEnabled($routerId)
                && filled(ProviderSmartOlt::getUrl($routerId))
                && filled(ProviderSmartOlt::getToken($routerId));
        } catch (\Throwable) {
            return false;
        }
    }

    /**
     * Resultado para mutaciones de tipo ActionResult cuando SmartOLT está apagado.
     */
    public static function blocked(?int $routerId = null): ?array
    {
        return self::enabled($routerId) ? null : ['success' => false, 'message' => self::DISABLED_MESSAGE];
    }

    /**
     * ¿Alguna zona (o la configuración por defecto) usa SmartOLT? Sirve para el menú.
     */
    public static function enabledAnywhere(): bool
    {
        if (self::enabled()) {
            return true;
        }
        $routerIds = \App\Models\CoreConfigData::where('path', ProviderSmartOlt::GENERAL_PATH . 'enabled')
            ->where('scope_id', '>', 0)
            ->where('value', '1')
            ->pluck('scope_id');

        return $routerIds->contains(fn ($id) => self::enabled((int) $id));
    }
}
