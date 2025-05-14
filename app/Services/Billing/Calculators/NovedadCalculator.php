<?php

namespace App\Services\Billing\Calculators;

use App\Models\BillingNovedad;
use App\Models\Services\Service;

interface NovedadCalculator
{
    /**
     * @param array $rule Datos variables (días, %, etc.) guardados en la novedad
     * @param Service $service Servicio afectado
     * @return float  Monto resultante (+ cargo / – descuento)
     */
    public function calculate(BillingNovedad $rule, Service $service): float;
}
