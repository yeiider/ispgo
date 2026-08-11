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

        /* 4️⃣  Días a facturar (Base 30 días) ----------------------- */
        $startDay = (int) ($rule['start_day'] ?? 1);
        if ($startDay > 30) $startDay = 30; // Tratar día 31 como 30
        
        $daysOfService = 30 - $startDay + 1;

        /* 5️⃣  Precio por día (Base 30 días) ------------------------ */
        $dailyPrice  = $service->plan->monthly_price / 30;

        /* 6️⃣  Resultado (Descuento por días no usados) -------------- */
        // El monto final a cobrar sería (dailyPrice * daysOfService)
        // La novedad es un descuento: (dailyPrice * daysOfService) - PrecioTotal
        $discount = ($dailyPrice * $daysOfService) - $service->plan->monthly_price;
        
        return -abs(round($discount));
    }
}
