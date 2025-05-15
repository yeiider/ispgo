<?php

namespace App\Services\Billing\Calculators;

use App\Models\BillingNovedad;
use App\Models\Services\Service;
use Carbon\Carbon;

class MoraCalculator implements NovedadCalculator
{
    /**
     * Calcula el cargo por mora (pago tardío).
     * Puede ser un monto fijo, un porcentaje del saldo pendiente,
     * o un interés diario sobre el saldo.
     */
    public function calculate(BillingNovedad $novedad, Service $service): float
    {
        /* 1️⃣  Obtener la regla ---------------------------------------- */
        $rule = $novedad->rule ?? [];

        /* 2️⃣  Determinar el tipo de cargo por mora -------------------- */
        $moraType = $rule['mora_type'] ?? 'fixed'; // 'fixed', 'percentage', o 'daily_interest'
        $moraValue = $rule['mora_value'] ?? 0;
        $pendingAmount = $rule['pending_amount'] ?? 0;

        if ($moraValue <= 0 || $pendingAmount <= 0) {
            return 0; // No hay cargo que aplicar o no hay saldo pendiente
        }

        /* 3️⃣  Calcular el monto del cargo según el tipo --------------- */
        switch ($moraType) {
            case 'percentage':
                // Porcentaje del saldo pendiente
                $percentage = min(100, max(0, $moraValue)); // Limitar entre 0-100%
                $moraAmount = $pendingAmount * ($percentage / 100);
                break;

            case 'daily_interest':
                // Interés diario sobre el saldo pendiente
                $dueDate = Carbon::parse($rule['due_date'] ?? null);
                $today = Carbon::now();

                if (!$dueDate || $today->lte($dueDate)) {
                    return 0; // No hay mora si no hay fecha de vencimiento o no ha vencido
                }

                $daysLate = $dueDate->diffInDays($today);
                $dailyRate = $moraValue / 100; // Convertir a decimal (ej: 0.1% = 0.001)
                $moraAmount = $pendingAmount * $dailyRate * $daysLate;
                break;

            case 'fixed':
            default:
                // Monto fijo
                $moraAmount = $moraValue;
                break;
        }

        /* 4️⃣  Aplicar límite máximo si existe ------------------------- */
        if (isset($rule['max_amount']) && $rule['max_amount'] > 0) {
            $moraAmount = min($moraAmount, $rule['max_amount']);
        }

        /* 5️⃣  Devolver el resultado como cargo (valor positivo) ------- */
        return abs(round($moraAmount, 2));
    }
}
