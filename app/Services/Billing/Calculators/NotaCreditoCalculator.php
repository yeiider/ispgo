<?php

namespace App\Services\Billing\Calculators;

use App\Models\BillingNovedad;
use App\Models\Services\Service;

class NotaCreditoCalculator implements NovedadCalculator
{
    /**
     * Calcula el monto de una nota de crédito.
     * Una nota de crédito es un descuento que se aplica a la factura
     * por diversos motivos (compensación, error de facturación, etc.)
     */
    public function calculate(BillingNovedad $novedad, Service $service): float
    {
        /* 1️⃣  Obtener la regla ---------------------------------------- */
        $rule = $novedad->rule ?? [];

        /* 2️⃣  Obtener el monto de la nota de crédito ------------------ */
        $creditAmount = $rule['credit_amount'] ?? $novedad->amount ?? 0;

        if ($creditAmount <= 0) {
            return 0; // No hay crédito que aplicar
        }

        /* 3️⃣  Aplicar límites si existen ------------------------------- */
        $monthlyPrice = $service->plan->monthly_price;

        // Opcionalmente, limitar el crédito al precio mensual del servicio
        if (isset($rule['limit_to_monthly_price']) && $rule['limit_to_monthly_price']) {
            $creditAmount = min($creditAmount, $monthlyPrice);
        }

        // Opcionalmente, aplicar un porcentaje máximo del precio mensual
        if (isset($rule['max_percentage']) && $rule['max_percentage'] > 0) {
            $maxPercentage = min(100, max(0, $rule['max_percentage']));
            $maxAmount = $monthlyPrice * ($maxPercentage / 100);
            $creditAmount = min($creditAmount, $maxAmount);
        }

        /* 4️⃣  Devolver el resultado como descuento (valor negativo) --- */
        return -abs(round($creditAmount, 2));
    }
}
