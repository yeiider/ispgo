<?php

namespace Ispgo\Smartolt\Listeners;


use App\Events\ServiceActive;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Log;
use Ispgo\Smartolt\Services\ApiManager;
use Ispgo\Smartolt\Settings\ProviderSmartOlt;

class ServiceOltManagerListenerActive
{

    /**
     * Handle the event.
     *
     * @param ServiceActive $event
     * @return void
     * @throws ConnectionException
     * @throws \Exception
     */
    public function handle(ServiceActive $event)
    {

        if (!ProviderSmartOlt::getEnabled()) {
            Log::info("SmartOLT no está habilitado.");
            return;
        }
        $service = $event->service;
        if (empty($service->sn)) {
            Log::warning("El servicio con ID {$service->id} no tiene un número de serie válido.");
            return;
        }
        $apiManager = new ApiManager();
        if ($service->service_status === 'active'){
            $apiManager->enableOnu($service->sn);
        }
    }


}
