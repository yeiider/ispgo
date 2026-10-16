<?php

namespace App\Jobs;

use App\Services\Oltgo\OltgoAlertDispatcher;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

/**
 * Procesa un lote de alertas recibido por el webhook de OLTGO.
 */
class ProcessOltgoAlerts implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    /** @param array<int, array<string, mixed>> $events */
    public function __construct(public array $events)
    {
    }

    public function handle(OltgoAlertDispatcher $dispatcher): void
    {
        $dispatcher->dispatch($this->events);
    }
}
