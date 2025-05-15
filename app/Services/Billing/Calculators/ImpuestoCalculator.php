<?php

namespace App\Services\Billing\Calculators;

use App\Models\BillingNovedad;
use App\Models\Services\Service;

class ImpuestoCalculator implements NovedadCalculator
{
    /**
     * Calcula los impuestos aplicables al servicio.
     * Puede ser un porcentaje del precio del servicio o un monto fijo.
     */
    public function calculate(BillingNovedad $novedad, Service $service): float
    {
        /* 1️⃣  Obtener la regla ---------------------------------------- */
        $rule = $novedad->rule ?? [];

        /* 2️⃣  Determinar el tipo de impuesto -------------------------- */
        $taxType = $rule['tax_type'] ?? 'percentage'; // 'percentage' o 'fixed'
        $taxValue = $rule['tax_value'] ?? 0;

        if ($taxValue <= 0) {
            return 0; // No hay impuesto que aplicar
        }

        /* 3️⃣  Determinar la base imponible ---------------------------- */
        $taxableAmount = $rule['taxable_amount'] ?? $service->plan->monthly_price;

        /* 4️⃣  Calcular el monto del impuesto -------------------------- */
        if ($taxType === 'percentage') {
            // Porcentaje de la base imponible
            $taxAmount = $taxableAmount * ($taxValue / 100);
        } else {
            // Monto fijo
            $taxAmount = $taxValue;
        }

        /* 5️⃣  Aplicar redondeo según configuración -------------------- */
        $roundingMode = $rule['rounding_mode'] ?? 'up'; // 'up', 'down', o 'nearest'
        $decimals = $rule['decimals'] ?? 2;

        switch ($roundingMode) {
            case 'up':
                $taxAmount = ceil($taxAmount * pow(10, $decimals)) / pow(10, $decimals);
                break;
            case 'down':
                $taxAmount = floor($taxAmount * pow(10, $decimals)) / pow(10, $decimals);
                break;
            case 'nearest':
            default:
                $taxAmount = round($taxAmount, $decimals);
                break;
        }

        /* 6️⃣  Devolver el resultado como cargo (valor positivo) ------- */
        return abs($taxAmount);
    }
}
