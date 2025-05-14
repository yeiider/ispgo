<?php

namespace App\Services\Billing\Calculators;

use App\Models\BillingNovedad;
use App\Models\Services\Service;

class SaldoFavorCalculator implements NovedadCalculator
{
    public function calculate(BillingNovedad $novedad, Service $service): float
    {
        // 1️⃣  Preferencia: monto explícito en la regla
        if ($novedad->amount) {
            return -abs($novedad->amount);
        }

        // 2️⃣  Si no viene, usa el saldo del cliente (ajusta el campo a tu modelo)
        //$credit = (float) ($service->customer->credit_balance ?? 0);

        return 0;          // descuento (valor negativo)
    }
}
