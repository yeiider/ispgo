<?php

namespace App\Listeners;

use App\Events\CotizacionCreated;
use App\Helpers\Notify;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class NotifyCotizacionCreated implements ShouldQueue
{
    use InteractsWithQueue;

    public $queue = 'redis';

    public $tries = 3;

    /**
     * Notifica a los administradores cuando se registra una cotización (marketing).
     */
    public function handle(CotizacionCreated $event): void
    {
        $cotizacion = $event->cotizacion;

        if (!$cotizacion) {
            return;
        }

        $name = trim(($cotizacion->nombre ?? '') . ' ' . ($cotizacion->apellido ?? '')) ?: 'Sin nombre';

        $message = "Nueva cotización: {$name}";
        if ($cotizacion->plan) {
            $message .= " — Plan: {$cotizacion->plan}";
        }

        Notify::notifyInfo(
            $message,
            'Cotización (marketing)',
            null,
            ['cotizacion_id' => $cotizacion->id]
        );
    }
}
