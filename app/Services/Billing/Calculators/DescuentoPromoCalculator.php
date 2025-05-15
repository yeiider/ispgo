<?php

namespace App\Services\Billing\Calculators;

use App\Models\BillingNovedad;
use App\Models\Services\Service;
use Carbon\Carbon;

class DescuentoPromoCalculator implements NovedadCalculator
{
    /**
     * Calcula el descuento promocional aplicable al servicio.
     * El descuento puede ser un porcentaje o un monto fijo.
     */
    public function calculate(BillingNovedad $novedad, Service $service): float
    {
        /* 1️⃣  Obtener la regla ---------------------------------------- */
        $rule = $novedad->rule ?? [];

        /* 2️⃣  Determinar el tipo de descuento ------------------------- */
        $discountType = $rule['discount_type'] ?? 'percentage'; // 'percentage' o 'fixed'
        $discountValue = $rule['discount_value'] ?? 0;

        if ($discountValue <= 0) {
            return 0; // No hay descuento que aplicar
        }

        /* 3️⃣  Calcular el monto del descuento ------------------------- */
        $monthlyPrice = $service->plan->monthly_price;

        if ($discountType === 'percentage') {
            // Asegurar que el porcentaje esté entre 0 y 100
            $percentage = min(100, max(0, $discountValue));
            $discountAmount = $monthlyPrice * ($percentage / 100);
        } else {
            // Para descuento fijo, asegurar que no exceda el precio mensual
            $discountAmount = min($monthlyPrice, $discountValue);
        }

        /* 4️⃣  Aplicar límites de tiempo si existen -------------------- */
        if (isset($rule['start_date']) && isset($rule['end_date'])) {
            $startDate = Carbon::parse($rule['start_date']);
            $endDate = Carbon::parse($rule['end_date']);
            $today = Carbon::now();

            // Verificar si estamos dentro del período de promoción
            if ($today->lt($startDate) || $today->gt($endDate)) {
                return 0; // Fuera del período de promoción
            }
        }

        /* 5️⃣  Devolver el resultado como descuento (valor negativo) --- */
        return -abs(round($discountAmount, 2));
    }
}
