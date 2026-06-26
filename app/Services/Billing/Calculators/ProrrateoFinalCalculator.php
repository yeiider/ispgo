<?php

namespace App\Services\Billing\Calculators;

use App\Models\BillingNovedad;
use App\Models\Services\Service;
use Carbon\Carbon;

class ProrrateoFinalCalculator implements NovedadCalculator
{
    /**
     * Calcula el cargo proporcional según los días de servicio efectivos
     * hasta la fecha fin especificada como 'end_day'.
     */
    public function calculate(BillingNovedad $novedad, Service $service): float
    {
        /* 1️⃣  Obtener la regla y la fecha del ciclo ------------------- */
        $rule = $novedad->rule ?? []; // array con configuraciones
        $periodStart = Carbon::parse($novedad->effective_period)
            ->startOfMonth(); // p.ej. 2025-05-01
        $periodYear = $periodStart->year;
        $periodMonth = $periodStart->month;

        /* 2️⃣  Construir fecha de inicio (día en el periodo) ------------ */
        $startDay = (int)($rule['start_day'] ?? $periodStart->day); // Día inicial del servicio
        $startDate = Carbon::createSafe($periodYear, $periodMonth, 1)->day($startDay);

        /* 3️⃣  Construir fecha de fin (end_day) ------------------------ */
        $endDay = (int)($rule['end_day'] ?? $periodStart->endOfMonth()->day); // Día final del servicio
        $endDate = Carbon::createSafe($periodYear, $periodMonth, 1)->day($endDay);

        if ($endDate->lessThan($startDate)) {
            throw new \InvalidArgumentException("La fecha de fin ('end_day') no puede ser anterior a la fecha de inicio ('start_day').");
        }

        /* 4️⃣  Días efectivos de servicio (Base 30 días) -------------- */
        $startDay = (int) ($rule['start_day'] ?? 1);
        $endDay = (int) ($rule['end_day'] ?? 30);
        
        if ($startDay > 30) $startDay = 30;
        if ($endDay > 30) $endDay = 30;

        $daysOfService = max(0, $endDay - $startDay + 1);

        /* 5️⃣  Precio por día (Base 30 días) -------------------------- */
        $dailyPrice = $service->plan->monthly_price / 30;

        /* 6️⃣  Calcular el valor proporcional (Descuento) ------------- */
        // El monto a cobrar es (dailyPrice * daysOfService)
        // El descuento es (Cobro - PrecioTotal)
        $discount = ($dailyPrice * $daysOfService) - $service->plan->monthly_price;

        /* 7️⃣  Devolver el resultado como carga negativa --------------- */
        return -abs(round($discount)); 
    }
}
