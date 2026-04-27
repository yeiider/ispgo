<?php

namespace Ispgo\Smartolt\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Ispgo\Smartolt\Services\ApiManager;
use Ispgo\Smartolt\Settings\ProviderSmartOlt;

/**
 * Job que reinicia una ONU (reboot).
 *
 * Se despacha con un delay de 5 segundos después de activar la ONU
 * (2s para el CATV + 3s adicionales para el reboot), asegurando que
 * el servicio CATV ya esté activo antes del reinicio.
 */
class RebootOnuJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $timeout = 60;

    public function __construct(
        private readonly string $externalId,
        private readonly int    $serviceId,
    ) {}

    public function handle(): void
    {
        if (!ProviderSmartOlt::getEnabled()) {
            Log::info("RebootOnuJob: SmartOLT no está habilitado. Omitiendo reboot para external_id {$this->externalId}.");
            return;
        }

        Log::info("RebootOnuJob: Reiniciando ONU external_id {$this->externalId} (servicio #{$this->serviceId}).");

        try {
            $apiManager = new ApiManager();
            $response = $apiManager->rebootOnuByExternalId($this->externalId);

            if ($response->successful()) {
                Log::info("RebootOnuJob: Reboot enviado correctamente para external_id {$this->externalId}.", [
                    'response' => $response->body(),
                ]);
            } else {
                Log::warning("RebootOnuJob: Fallo al reiniciar ONU para external_id {$this->externalId}.", [
                    'status'   => $response->status(),
                    'response' => $response->body(),
                ]);
            }
        } catch (\Exception $e) {
            Log::error("RebootOnuJob: Excepción al reiniciar ONU para external_id {$this->externalId}: {$e->getMessage()}");
            throw $e; // Permitir reintento
        }
    }
}
