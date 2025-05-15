<?php

namespace Tests\Unit\Services\Billing\Calculators;

use App\Models\BillingNovedad;
use App\Models\Services\Service;
use App\Services\Billing\Calculators\CargoAdicionalCalculator;
use Mockery;
use PHPUnit\Framework\TestCase;

class CargoAdicionalCalculatorTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    /**
     * Test that calculate returns the absolute value of the novelty amount
     */
    public function test_calculate_returns_absolute_value_of_amount(): void
    {
        // Arrange
        $calculator = new CargoAdicionalCalculator();

        $novedad = Mockery::mock(BillingNovedad::class);
        $novedad->amount = -100.50;

        $service = Mockery::mock(Service::class);

        // Act
        $result = $calculator->calculate($novedad, $service);

        // Assert
        $this->assertEquals(100.50, $result);
    }

    /**
     * Test that calculate returns the same value for positive amounts
     */
    public function test_calculate_returns_same_value_for_positive_amount(): void
    {
        // Arrange
        $calculator = new CargoAdicionalCalculator();

        $novedad = Mockery::mock(BillingNovedad::class);
        $novedad->amount = 75.25;

        $service = Mockery::mock(Service::class);

        // Act
        $result = $calculator->calculate($novedad, $service);

        // Assert
        $this->assertEquals(75.25, $result);
    }

    /**
     * Test that calculate returns zero for zero amount
     */
    public function test_calculate_returns_zero_for_zero_amount(): void
    {
        // Arrange
        $calculator = new CargoAdicionalCalculator();

        $novedad = Mockery::mock(BillingNovedad::class);
        $novedad->amount = 0;

        $service = Mockery::mock(Service::class);

        // Act
        $result = $calculator->calculate($novedad, $service);

        // Assert
        $this->assertEquals(0, $result);
    }
}
