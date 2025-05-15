<?php

namespace App\Services\Billing\Calculators;

use App\Models\BillingNovedad;
use App\Models\Services\Service;
use Carbon\Carbon;

class CompensacionCalculator implements NovedadCalculator
{
    /**
     * Calcula la compensación por problemas de servicio.
     * Puede ser un monto fijo, un porcentaje del precio mensual,
     * o proporcional a los días sin servicio.
     */
    public function calculate(BillingNovedad $novedad, Service $service): float
    {
        /* 1️⃣  Obtener la regla ---------------------------------------- */
        $rule = $novedad->rule ?? [];

        /* 2️⃣  Determinar el tipo de compensación ----------------------- */
        $compensationType = $rule['compensation_type'] ?? 'days'; // 'fixed', 'percentage', o 'days'
        $compensationValue = $rule['compensation_value'] ?? 0;

        if ($compensationValue <= 0) {
            return 0; // No hay compensación que aplicar
        }

        /* 3️⃣  Calcular el monto de compensación según el tipo ---------- */
        $monthlyPrice = $service->plan->monthly_price;

        switch ($compensationType) {
            case 'percentage':
                // Porcentaje del precio mensual
                $percentage = min(100, max(0, $compensationValue)); // Limitar entre 0-100%
                $compensationAmount = $monthlyPrice * ($percentage / 100);
                break;

            case 'days':
                // Proporcional a los días sin servicio
                $startDate = Carbon::parse($rule['outage_start'] ?? null);
                $endDate = Carbon::parse($rule['outage_end'] ?? null);

                if (!$startDate || !$endDate) {
                    return 0; // No se pueden calcular días sin fechas
                }

                $daysWithoutService = $startDate->diffInDays($endDate) + 1;
                $daysInMonth = Carbon::parse($novedad->effective_period)->daysInMonth;
                $dailyPrice = $monthlyPrice / $daysInMonth;

                $compensationAmount = $dailyPrice * $daysWithoutService;
                break;

            case 'fixed':
            default:
                // Monto fijo
                $compensationAmount = $compensationValue;
                break;
        }

        /* 4️⃣  Aplicar factor de compensación si existe ----------------- */
        if (isset($rule['compensation_factor']) && $rule['compensation_factor'] > 0) {
            $compensationAmount *= $rule['compensation_factor'];
        }

        /* 5️⃣  Devolver el resultado como descuento (valor negativo) ---- */
        return -abs(round($compensationAmount, 2));
    }
}
