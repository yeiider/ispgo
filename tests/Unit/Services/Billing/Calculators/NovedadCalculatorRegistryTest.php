<?php

namespace Tests\Unit\Services\Billing\Calculators;

use App\Models\BillingNovedad;
use App\Services\Billing\Calculators\CargoAdicionalCalculator;
use App\Services\Billing\Calculators\NovedadCalculator;
use App\Services\Billing\Calculators\NovedadCalculatorRegistry;
use App\Services\Billing\Calculators\ProrrateoFinalCalculator;
use App\Services\Billing\Calculators\ProrrateoInicialCalculator;
use App\Services\Billing\Calculators\SaldoFavorCalculator;
use Mockery;
use PHPUnit\Framework\TestCase;

class NovedadCalculatorRegistryTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    /**
     * Test that the registry returns the correct calculator for each type
     */
    public function test_for_returns_correct_calculator_for_each_type(): void
    {
        // Arrange
        $cargoAdicional = Mockery::mock(CargoAdicionalCalculator::class);
        $saldoFavor = Mockery::mock(SaldoFavorCalculator::class);
        $prorrateoIni = Mockery::mock(ProrrateoInicialCalculator::class);
        $prorrateoFin = Mockery::mock(ProrrateoFinalCalculator::class);

        $registry = new NovedadCalculatorRegistry(
            $cargoAdicional,
            $saldoFavor,
            $prorrateoIni,
            $prorrateoFin
        );

        // Act & Assert
        $this->assertSame($cargoAdicional, $registry->for(BillingNovedad::T_CARGO_ADICIONAL));
        $this->assertSame($saldoFavor, $registry->for(BillingNovedad::T_SALDO_FAVOR));
        $this->assertSame($prorrateoIni, $registry->for(BillingNovedad::T_PRORRATEO_INI));
        $this->assertSame($prorrateoFin, $registry->for(BillingNovedad::T_PRORRATEO_FIN));
    }

    /**
     * Test that the registry throws an exception for an unknown type
     */
    public function test_for_throws_exception_for_unknown_type(): void
    {
        // Arrange
        $cargoAdicional = Mockery::mock(CargoAdicionalCalculator::class);
        $saldoFavor = Mockery::mock(SaldoFavorCalculator::class);
        $prorrateoIni = Mockery::mock(ProrrateoInicialCalculator::class);
        $prorrateoFin = Mockery::mock(ProrrateoFinalCalculator::class);

        $registry = new NovedadCalculatorRegistry(
            $cargoAdicional,
            $saldoFavor,
            $prorrateoIni,
            $prorrateoFin
        );

        // Assert
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage("Sin calculador para [unknown_type]");

        // Act
        $registry->for('unknown_type');
    }
}
