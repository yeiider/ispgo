<?php

namespace App\Console\Commands;

use App\Models\SmartOltBatch;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Ispgo\Smartolt\Jobs\ProcessSmartOltBatch;
use Ispgo\Smartolt\Services\ApiManager;
use Illuminate\Support\Facades\Log;

class ProcessSmartOltBatches extends Command
{
    protected $signature = 'smartolt:process_batches';
    protected $description = 'Procesa los lotes acumulados para enviar a SmartOLT';

    public function handle()
    {
        $batches = SmartOltBatch::all();
        $delay = 0;

        foreach ($batches as $batch) {
            $chunks = array_chunk($batch->sn_list, 10);

            foreach ($chunks as $chunk) {
                dispatch(new ProcessSmartOltBatch($chunk, $batch->action))->delay(now()->addSeconds($delay))->onQueue('redis');
                $delay += 10;
            }

            $batch->delete(); // Eliminar después de procesar
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
