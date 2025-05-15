<?php

namespace App\Services\Billing\Calculators;

use App\Models\BillingNovedad;
use App\Models\Services\Service;
use Carbon\Carbon;

class CambioPlanCalculator implements NovedadCalculator
{
    /**
     * Calcula el ajuste de precio cuando un cliente cambia de plan.
     * Si el nuevo plan es más caro, se cobra la diferencia proporcional.
     * Si el nuevo plan es más barato, se devuelve la diferencia proporcional.
     */
    public function calculate(BillingNovedad $novedad, Service $service): float
    {
        /* 1️⃣  Obtener la regla y la fecha del ciclo ------------------- */
        $rule = $novedad->rule ?? [];
        $periodStart = Carbon::parse($novedad->effective_period)
            ->startOfMonth(); // p.ej. 2025-05-01
        $periodYear = $periodStart->year;
        $periodMonth = $periodStart->month;

        /* 2️⃣  Verificar que exista el nuevo plan ----------------------- */
        $newPlanId = $rule['new_plan_id'] ?? null;

        if (!$newPlanId) {
            throw new \InvalidArgumentException("Se requiere el ID del nuevo plan para calcular el cambio de plan.");
        }

        /* 3️⃣  Obtener el plan actual del servicio y el nuevo plan ------ */
        $currentPlan = $service->plan;
        $newPlan = \App\Models\Services\Plan::findOrFail($newPlanId);

        if (!$currentPlan) {
            throw new \InvalidArgumentException("El servicio no tiene un plan asociado.");
        }

        /* 4️⃣  Construir fecha de cambio (día dentro del periodo) ------ */
        $changeDay = (int)($rule['change_day'] ?? Carbon::now()->day);
        $changeDate = Carbon::createSafe($periodYear, $periodMonth, 1)->day($changeDay);

        /* 5️⃣  Calcular días restantes en el mes ----------------------- */
        $daysInMonth = $periodStart->daysInMonth;
        $daysRemaining = $changeDate->diffInDays($periodStart->copy()->endOfMonth()) + 1;

        /* 6️⃣  Calcular precios diarios ------------------------------- */
        $currentDailyPrice = $currentPlan->monthly_price / $daysInMonth;
        $newDailyPrice = $newPlan->monthly_price / $daysInMonth;

        /* 7️⃣  Calcular la diferencia proporcional -------------------- */
        $currentRemainingCost = $currentDailyPrice * $daysRemaining;
        $newRemainingCost = $newDailyPrice * $daysRemaining;
        $priceDifference = $newRemainingCost - $currentRemainingCost;

        /* 8️⃣  Devolver el resultado (positivo si es cargo, negativo si es descuento) */
        return round($priceDifference, 2);
    }
}
