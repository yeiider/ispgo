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
 * Job que fuerza un ciclo CATV disable → enable para que el equipo
 * físico aplique realmente el servicio de TV al cliente.
 *
 * SmartOLT puede reportar "CATV enabled" en su API, pero el equipo
 * no aplica el servicio de TV hasta que se realiza un ciclo explícito
 * de apagado y encendido de CATV después de que la ONU haya reiniciado.
 */
class ToggleCatvJob implements ShouldQueue
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
            Log::info("ToggleCatvJob: SmartOLT no está habilitado. Omitiendo toggle CATV para external_id {$this->externalId}.");
            return;
        }

        Log::info("ToggleCatvJob: Iniciando ciclo disable→enable CATV para external_id {$this->externalId} (servicio #{$this->serviceId}).");

        try {
            $apiManager = new ApiManager();

            // Paso 1: Disable CATV
            $disableResponse = $apiManager->disableOnuCatvByExternalId($this->externalId);

            if ($disableResponse->successful()) {
                Log::info("ToggleCatvJob: CATV deshabilitado para external_id {$this->externalId}.", [
                    'response' => $disableResponse->body(),
                ]);
            } else {
                Log::warning("ToggleCatvJob: Fallo al deshabilitar CATV para external_id {$this->externalId}.", [
                    'status'   => $disableResponse->status(),
                    'response' => $disableResponse->body(),
                ]);
            }

            // Esperar 3 segundos entre disable y enable para que el equipo procese
            sleep(3);

            // Paso 2: Enable CATV
            $enableResponse = $apiManager->enableOnuCatvByExternalId($this->externalId);

            if ($enableResponse->successful()) {
                Log::info("ToggleCatvJob: CATV re-habilitado correctamente para external_id {$this->externalId}.", [
                    'response' => $enableResponse->body(),
                ]);
            } else {
                Log::warning("ToggleCatvJob: Fallo al re-habilitar CATV para external_id {$this->externalId}.", [
                    'status'   => $enableResponse->status(),
                    'response' => $enableResponse->body(),
                ]);
            }
        } catch (\Exception $e) {
            Log::error("ToggleCatvJob: Excepción en ciclo CATV para external_id {$this->externalId}: {$e->getMessage()}");
            throw $e; // Permitir reintento
        }
    }
}
