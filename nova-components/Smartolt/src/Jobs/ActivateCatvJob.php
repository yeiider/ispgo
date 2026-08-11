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
 * Job que activa el CATV de una ONU.
 *
 * Se despacha con un delay de 2 segundos después de activar la ONU,
 * dando tiempo al equipo para inicializarse antes de habilitar el servicio CATV.
 */
class ActivateCatvJob implements ShouldQueue
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
            Log::info("ActivateCatvJob: SmartOLT no está habilitado. Omitiendo CATV para external_id {$this->externalId}.");
            return;
        }

        Log::info("ActivateCatvJob: Activando CATV para external_id {$this->externalId} (servicio #{$this->serviceId}).");

        try {
            $apiManager = new ApiManager();
            $response = $apiManager->enableOnuCatvByExternalId($this->externalId);

            if ($response->successful()) {
                Log::info("ActivateCatvJob: CATV activado correctamente para external_id {$this->externalId}.", [
                    'response' => $response->body(),
                ]);
            } else {
                Log::warning("ActivateCatvJob: Fallo al activar CATV para external_id {$this->externalId}.", [
                    'status'   => $response->status(),
                    'response' => $response->body(),
                ]);
            }
        } catch (\Exception $e) {
            Log::error("ActivateCatvJob: Excepción al activar CATV para external_id {$this->externalId}: {$e->getMessage()}");
            throw $e; // Permitir reintento
        }
    }
}
