<?php

namespace App\Services\Billing\Calculators;

use App\Models\BillingNovedad;
use App\Models\Services\Service;

class ExcesoConsumoCalculator implements NovedadCalculator
{
    /**
     * Calcula el cargo por exceso de consumo de datos.
     * Se aplica cuando el cliente supera el límite de datos incluido en su plan.
     */
    public function calculate(BillingNovedad $novedad, Service $service): float
    {
        /* 1️⃣  Obtener la regla ---------------------------------------- */
        $rule = $novedad->rule ?? [];

        /* 2️⃣  Verificar si el plan tiene límite de datos --------------- */
        if ($service->plan->unlimited_data) {
            return 0; // No hay cargo por exceso en planes ilimitados
        }

        /* 3️⃣  Obtener datos de consumo y límite ----------------------- */
        $dataLimit = $service->plan->data_limit ?? 0; // En GB
        $dataUsed = $rule['data_used'] ?? 0; // En GB
        $excessData = max(0, $dataUsed - $dataLimit); // Exceso en GB

        if ($excessData <= 0) {
            return 0; // No hay exceso de consumo
        }

        /* 4️⃣  Determinar el tipo de cargo por exceso ------------------ */
        $chargeType = $rule['charge_type'] ?? 'per_unit'; // 'per_unit', 'tiered', o 'flat'

        /* 5️⃣  Calcular el cargo según el tipo ------------------------- */
        switch ($chargeType) {
            case 'tiered':
                // Cargo por niveles (diferentes tarifas según la cantidad de exceso)
                $tiers = $rule['tiers'] ?? [];
                $charge = 0;

                if (empty($tiers)) {
                    // Si no hay niveles definidos, usar la tarifa base del plan
                    $charge = $excessData * ($service->plan->overage_fee ?? 0);
                } else {
                    // Ordenar niveles por límite ascendente
                    usort($tiers, function ($a, $b) {
                        return ($a['limit'] ?? 0) <=> ($b['limit'] ?? 0);
                    });

                    $remainingExcess = $excessData;
                    foreach ($tiers as $tier) {
                        $tierLimit = $tier['limit'] ?? PHP_FLOAT_MAX;
                        $tierRate = $tier['rate'] ?? 0;

                        if ($remainingExcess <= 0) {
                            break;
                        }

                        $tierUsage = min($remainingExcess, $tierLimit);
                        $charge += $tierUsage * $tierRate;
                        $remainingExcess -= $tierUsage;
                    }
                }
                break;

            case 'flat':
                // Cargo fijo sin importar la cantidad de exceso
                $charge = $rule['flat_fee'] ?? ($service->plan->overage_fee ?? 0);
                break;

            case 'per_unit':
            default:
                // Cargo por unidad de exceso (GB)
                $unitRate = $rule['unit_rate'] ?? ($service->plan->overage_fee ?? 0);
                $charge = $excessData * $unitRate;
                break;
        }

        /* 6️⃣  Aplicar límite máximo si existe ------------------------- */
        if (isset($rule['max_charge']) && $rule['max_charge'] > 0) {
            $charge = min($charge, $rule['max_charge']);
        }

        /* 7️⃣  Devolver el resultado como cargo (valor positivo) ------- */
        return abs(round($charge, 2));
    }
}
