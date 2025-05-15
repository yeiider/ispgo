<?php

namespace App\Services\Billing\Calculators;

use App\Models\BillingNovedad;

class NovedadCalculatorRegistry
{
    public function __construct(
        protected CargoAdicionalCalculator   $cargoAdicional,
        protected SaldoFavorCalculator       $saldoFavor,
        protected ProrrateoInicialCalculator $prorrateoIni,
        protected ProrrateoFinalCalculator   $prorrateoFin,
        protected CambioPlanCalculator       $cambioPlan,
        protected DescuentoPromoCalculator   $descuentoPromo,
        protected CargoReconexionCalculator  $cargoReconexion,
        protected MoraCalculator             $mora,
        protected NotaCreditoCalculator      $notaCredito,
        protected CompensacionCalculator     $compensacion,
        protected ExcesoConsumoCalculator    $excesoConsumo,
        protected ImpuestoCalculator         $impuesto,
        protected EntregaProductoCalculator  $entregaProducto
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
            BillingNovedad::T_CAMBIO_PLAN => $this->cambioPlan,
            BillingNovedad::T_DESCUENTO_PROMO => $this->descuentoPromo,
            BillingNovedad::T_CARGO_RECONEXION => $this->cargoReconexion,
            BillingNovedad::T_MORA => $this->mora,
            BillingNovedad::T_NOTA_CREDITO => $this->notaCredito,
            BillingNovedad::T_COMPENSACION => $this->compensacion,
            BillingNovedad::T_EXCESO_CONSUMO => $this->excesoConsumo,
            BillingNovedad::T_IMPUESTO => $this->impuesto,
            BillingNovedad::T_ENTREGA_PRODUCTO => $this->entregaProducto,
            default => throw new \LogicException("Sin calculador para [$type]")
        };
    }
}
