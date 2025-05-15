<?php

namespace App\Services\Billing\Calculators;

use App\Models\BillingNovedad;
use App\Models\Services\Service;

class CargoReconexionCalculator implements NovedadCalculator
{
    /**
     * Calcula el cargo por reconexión de un servicio suspendido.
     * Puede ser un monto fijo o un porcentaje del precio mensual.
     */
    public function calculate(BillingNovedad $novedad, Service $service): float
    {
        /* 1️⃣  Obtener la regla ---------------------------------------- */
        $rule = $novedad->rule ?? [];

        /* 2️⃣  Determinar el tipo de cargo ------------------------------ */
        $chargeType = $rule['charge_type'] ?? 'fixed'; // 'fixed' o 'percentage'
        $chargeValue = $rule['charge_value'] ?? 0;

        if ($chargeValue <= 0) {
            return 0; // No hay cargo que aplicar
        }

        /* 3️⃣  Calcular el monto del cargo ----------------------------- */
        $monthlyPrice = $service->plan->monthly_price;

        if ($chargeType === 'percentage') {
            // Asegurar que el porcentaje sea razonable (0-100%)
            $percentage = min(100, max(0, $chargeValue));
            $chargeAmount = $monthlyPrice * ($percentage / 100);
        } else {
            // Para cargo fijo, usar el valor directamente
            $chargeAmount = $chargeValue;
        }

        /* 4️⃣  Devolver el resultado como cargo (valor positivo) ------- */
        return abs(round($chargeAmount, 2));
    }
}
