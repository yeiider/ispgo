<?php

namespace Ispgo\Smartolt\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Ispgo\Smartolt\Services\ApiManager;
use Illuminate\Support\Facades\Log;

class ProcessSmartOltBatch implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;



    public $snList;
    public $action;

    /**
     * Create a new job instance.
     *
     * @param array $snList
     * @param string $action
     */
    public function __construct(array $snList, string $action)
    {
        $this->snList = $snList;
        $this->action = $action;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Un lote puede mezclar ONUs de varias zonas: cada zona va con su propia cuenta de SmartOLT.
        $byRouter = collect($this->snList)->groupBy(fn ($sn) => \App\Support\RouterScope::fromSn($sn) ?? 0);
        if ($byRouter->count() > 1) {
            foreach ($byRouter as $sns) {
                dispatch(new self($sns->values()->all(), $this->action))->onQueue('redis');
            }
            return;
        }

        $routerId = ((int) $byRouter->keys()->first()) ?: null;
        if (!\App\Support\SmartOltGate::enabled($routerId)) {
            Log::info('ProcessSmartOltBatch: SmartOLT deshabilitado para la zona', ['router_id' => $routerId]);
            return;
        }
        $apiManager = new ApiManager($routerId);
        $payload = ['onus_external_ids' => implode(',', $this->snList)];
        Log::info("procesando lote ". implode(',', $this->snList));

        try {
            $catvResponse = null;
            if ($this->action === 'enable') {
                $response = $apiManager->enableBulk($payload);
                $catvResponse = $apiManager->enableCatvBulk($payload);
            } else {
                $response = $apiManager->disableBulk($payload);
                $catvResponse = $apiManager->disableCatvBulk($payload);
            }

            if ($response->successful()) {
                Log::info("Lote '{$this->action}' procesado exitosamente.", [
                    'sn_list' => $this->snList,
                    'response' => $response->body(),
                ]);
            } else {
                Log::error("Error al procesar el lote '{$this->action}'.", [
                    'sn_list' => $this->snList,
                    'response' => $response->body(),
                ]);
            }

            if (isset($catvResponse)) {
                if ($catvResponse->successful()) {
                    Log::info("CATV lote '{$this->action}' procesado exitosamente.", [
                        'sn_list' => $this->snList,
                        'response' => $catvResponse->body(),
                    ]);
                } else {
                    Log::warning("Error al procesar CATV lote '{$this->action}'.", [
                        'sn_list' => $this->snList,
                        'response' => $catvResponse->body(),
                    ]);
                }
            }
        } catch (\Exception $e) {
            Log::error("Excepción al procesar el lote '{$this->action}': {$e->getMessage()}");
        }
    }
}
