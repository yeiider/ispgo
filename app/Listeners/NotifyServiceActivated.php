<?php

namespace App\Listeners;

use App\Events\ServiceActive;
use App\Helpers\Notify;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class NotifyServiceActivated implements ShouldQueue
{
    use InteractsWithQueue;

    public $queue = 'redis';

    public $tries = 3;

    /**
     * Notifica a los administradores cuando un servicio es activado.
     */
    public function handle(ServiceActive $event): void
    {
        $service = $event->service;

        if (!$service) {
            return;
        }

        $customerName = $service->customer
            ? trim(($service->customer->first_name ?? '') . ' ' . ($service->customer->last_name ?? ''))
            : "Cliente #{$service->customer_id}";

        Notify::notifySuccess(
            "Servicio activado: {$customerName}",
            'Servicio activado',
            null,
            ['service_id' => $service->id, 'customer_id' => $service->customer_id]
        );
    }
}
