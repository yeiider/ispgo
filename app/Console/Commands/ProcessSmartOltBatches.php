<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Ispgo\Smartolt\Services\ApiManager;
use Illuminate\Support\Facades\Log;

class ProcessSmartOltBatches extends Command
{
    protected $signature = 'smartolt:process_batches';
    protected $description = 'Procesa los lotes acumulados para enviar a SmartOLT';

    public function handle()
    {
        $actions = ['enable', 'disable'];

        foreach ($actions as $action) {
            $cacheKey = "smartolt_batch_{$action}";

            $snList = Cache::pull($cacheKey, []);

            if (!empty($snList)) {
                // Dividir la lista en lotes de 10
                $chunks = array_chunk($snList, 10);
                $delay = 0;

                foreach ($chunks as $chunk) {
                    // Programar el envío del lote con un retraso
                    $this->dispatchBatch($chunk, $action, $delay);
                    $delay += 10; // Incrementar el retraso para el siguiente lote
                }
            }
        }
    }

    /**
     * Despacha el procesamiento de un lote con un retraso.
     *
     * @param array $snList
     * @param string $action
     * @param int $delay
     * @return void
     */
    private function dispatchBatch(array $snList, string $action, int $delay): void
    {
        $job = new \Ispgo\Smartolt\Jobs\ProcessSmartOltBatch($snList, $action);
        dispatch($job)->delay(now()->addSeconds($delay))->onQueue('redis');
    }
}
