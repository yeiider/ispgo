<?php

namespace App\Services\Billing\Calculators;

namespace App\Services\Billing\Calculators;

use App\Models\BillingNovedad;
use App\Models\Services\Service;
use Carbon\Carbon;

class CargoAdicionalCalculator implements NovedadCalculator
{
    /**
     * Calcula el cargo proporcional cuando el servicio comienza
     * después del primer día del ciclo.
     */
    public function calculate(BillingNovedad $novedad, Service $service): float
    {
        return abs($novedad->amount);
    }
}
