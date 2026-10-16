<?php

namespace App\Support;

use App\Models\Services\Service;

/**
 * Resuelve la zona (router) de la que sale la configuración: la del servicio
 * si la tiene, o null para usar la configuración por defecto.
 */
class RouterScope
{
    public static function fromService(?Service $service): ?int
    {
        $routerId = $service?->router_id ?? $service?->customer?->router_id;

        return $routerId ? (int) $routerId : null;
    }

    public static function fromServiceId(int|string|null $serviceId): ?int
    {
        if (!$serviceId) {
            return null;
        }

        return self::fromService(Service::withoutGlobalScopes()->find($serviceId));
    }

    /**
     * Busca el servicio dueño de una ONU por su serial (SmartOLT usa el serial como external_id).
     */
    public static function fromSn(?string $sn): ?int
    {
        if (blank($sn)) {
            return null;
        }

        return self::fromService(Service::withoutGlobalScopes()->where('sn', $sn)->first());
    }
}
