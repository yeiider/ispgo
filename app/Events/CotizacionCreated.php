<?php

namespace App\Events;

use App\Models\Cotizacion;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CotizacionCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public Cotizacion $cotizacion)
    {
    }
}
