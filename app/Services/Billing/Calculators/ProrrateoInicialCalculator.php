<?php
namespace App\Services\Billing\Calculators;

namespace App\Services\Billing\Calculators;

use App\Models\BillingNovedad;
use App\Models\Services\Service;
use Carbon\Carbon;

class ProrrateoInicialCalculator implements NovedadCalculator
{
    /**
     * Calcula el cargo proporcional cuando el servicio comienza
     * después del primer día del ciclo.
     */
    public function calculate(BillingNovedad $novedad, Service $service): float
    {
        /* 1️⃣  Obtener la regla y la fecha del ciclo ------------------- */
        $rule           = $novedad->rule ?? [];                 // array
        $periodStart    = Carbon::parse($novedad->effective_period)
            ->startOfMonth();               // p.ej. 2025-05-01
        $periodYear     = $periodStart->year;
        $periodMonth    = $periodStart->month;

        /* 2️⃣  Construir fecha de inicio (día dentro del periodo) ------ */
        $day = (int) ($rule['start_day'] ?? $periodStart->day); // 1-31
        // createSafe evita fechas inválidas (31 feb → 29 feb)
        $start = Carbon::createSafe($periodYear, $periodMonth, 1)->day($day);

        /* 3️⃣  Fin del periodo ---------------------------------------- */
        if (isset($rule['billing_day'])) {
            $periodEnd = Carbon::createSafe($periodYear, $periodMonth, 1)
                ->day($rule['billing_day']);
            if ($periodEnd->lessThan($start)) {
                $periodEnd->addMonth();        // corte pasa al mes siguiente
            }
        } else {
            $periodEnd = $periodStart->copy()->endOfMonth();
        }

        /* 4️⃣  Días a facturar --------------------------------------- */
        $includeToday  = $rule['include_today'] ?? true;
        $daysRemaining = $start->diffInDays($periodEnd) + ($includeToday ? 1 : 0);

        /* 5️⃣  Precio por día ---------------------------------------- */
        $daysInCycle = $periodStart->diffInDays($periodEnd) + 1;
        $dailyPrice  = $service->plan->monthly_price / $daysInCycle;

        /* 6️⃣  Resultado (+cargo) ------------------------------------ */
        return -abs(round(($dailyPrice * $daysRemaining)-$service->plan->monthly_price));
    }
}
