<?php

namespace App\Services\Billing\Calculators;

use App\Models\BillingNovedad;

class NovedadCalculatorRegistry
{
    public function __construct(
        protected CargoAdicionalCalculator   $cargoAdicional,
        protected SaldoFavorCalculator       $saldoFavor,
        protected ProrrateoInicialCalculator $prorrateoIni,
        protected ProrrateoFinalCalculator $prorrateoFin
    )
    {
    }


    public function for(string $type): NovedadCalculator
    {
        return match ($type) {
            BillingNovedad::T_PRORRATEO_INI => $this->prorrateoIni,
            BillingNovedad::T_SALDO_FAVOR => $this->saldoFavor,
            BillingNovedad::T_CARGO_ADICIONAL => $this->cargoAdicional,
            BillingNovedad::T_PRORRATEO_FIN => $this->prorrateoFin,
            default => throw new \LogicException("Sin calculador para [$type]")
        };
    }
}
