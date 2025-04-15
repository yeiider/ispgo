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
        $apiManager = new ApiManager();
        $payload = ['onus_external_ids' => implode(',', $this->snList)];
        Log::info("procesando lote ". implode(',', $this->snList));

        try {
            if ($this->action === 'enable') {
                $response = $apiManager->enableBulk($payload);
            } else {
                $response = $apiManager->disableBulk($payload);
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
        } catch (\Exception $e) {
            Log::error("Excepción al procesar el lote '{$this->action}': {$e->getMessage()}");
        }
    }
}
