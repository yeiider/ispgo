<?php

namespace Ispgo\Smartolt\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Collection;
use Ispgo\Smartolt\Services\ApiManager;
use Illuminate\Support\Facades\Log;

class SuspendServicesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $services;

    /**
     * Create a new job instance.
     *
     * @param \Illuminate\Support\Collection $services
     */
    public function __construct(Collection $services)
    {
        $this->services = $services;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Construir el payload
        $onusExternalIds = $this->services->pluck('sn')->implode(',');
        $payload = ['onus_external_ids' => $onusExternalIds];

        $apiManager = new ApiManager();

        try {
            $response = $apiManager->disableBulk($payload);

            if ($response->successful()) {
                // Opcionalmente, puedes registrar que la operación fue exitosa
                Log::info('Servicios suspendidos en SmartOLT', [
                    'payload' => $payload,
                    'response' => $response->body(),
                ]);
            } else {
                // Manejar errores de la respuesta
                Log::error('Error al suspender servicios en SmartOLT', [
                    'payload' => $payload,
                    'response' => $response->body(),
                ]);
            }
        } catch (\Exception $e) {
            // Manejar excepciones de conexión u otros errores
            Log::error('Excepción al comunicarse con SmartOLT', [
                'message' => $e->getMessage(),
                'payload' => $payload,
            ]);
        }
    }
}
