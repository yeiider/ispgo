<?php

namespace Tests\Unit\Services\Billing\Calculators;

use App\Models\BillingNovedad;
use App\Models\Services\Plan;
use App\Models\Services\Service;
use App\Services\Billing\Calculators\CargoReconexionCalculator;
use Mockery;
use PHPUnit\Framework\TestCase;

class CargoReconexionCalculatorTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    /**
     * Test that calculate returns correct fixed charge
     */
    public function test_calculate_returns_correct_fixed_charge(): void
    {
        // Arrange
        $calculator = new CargoReconexionCalculator();

        // Mock the service
        $service = Mockery::mock(Service::class);

        // Mock the novedad with fixed charge
        $novedad = Mockery::mock(BillingNovedad::class);
        $novedad->rule = [
            'charge_type' => 'fixed',
            'charge_value' => 25.00, // $25 reconnection fee
        ];

        // Act
        $result = $calculator->calculate($novedad, $service);

        // Assert - should be positive (charge)
        $this->assertGreaterThan(0, $result);
        $this->assertEquals(25.00, $result);
    }

    /**
     * Test that calculate returns correct percentage charge
     */
    public function test_calculate_returns_correct_percentage_charge(): void
    {
        // Arrange
        $calculator = new CargoReconexionCalculator();

        // Mock the plan
        $plan = Mockery::mock(Plan::class);
        $plan->monthly_price = 100.00;

        // Mock the service
        $service = Mockery::mock(Service::class);
        $service->plan = $plan;

        // Mock the novedad with percentage charge
        $novedad = Mockery::mock(BillingNovedad::class);
        $novedad->rule = [
            'charge_type' => 'percentage',
            'charge_value' => 20, // 20% of monthly price
        ];

        // Act
        $result = $calculator->calculate($novedad, $service);

        // Assert - should be positive (charge)
        $this->assertGreaterThan(0, $result);
        $this->assertEquals(20.00, $result); // 20% of $100 = $20
    }

    /**
     * Test that calculate limits percentage to 100%
     */
    public function test_calculate_limits_percentage_to_100_percent(): void
    {
        // Arrange
        $calculator = new CargoReconexionCalculator();

        // Mock the plan
        $plan = Mockery::mock(Plan::class);
        $plan->monthly_price = 100.00;

        // Mock the service
        $service = Mockery::mock(Service::class);
        $service->plan = $plan;

        // Mock the novedad with percentage charge over 100%
        $novedad = Mockery::mock(BillingNovedad::class);
        $novedad->rule = [
            'charge_type' => 'percentage',
            'charge_value' => 150, // 150% of monthly price (should be limited to 100%)
        ];

        // Act
        $result = $calculator->calculate($novedad, $service);

        // Assert - should be limited to 100% of monthly price
        $this->assertGreaterThan(0, $result);
        $this->assertEquals(100.00, $result); // 100% of $100 = $100
    }

    /**
     * Test that calculate returns zero for zero charge value
     */
    public function test_calculate_returns_zero_for_zero_charge_value(): void
    {
        // Arrange
        $calculator = new CargoReconexionCalculator();

        // Mock the service
        $service = Mockery::mock(Service::class);

        // Mock the novedad with zero charge
        $novedad = Mockery::mock(BillingNovedad::class);
        $novedad->rule = [
            'charge_type' => 'fixed',
            'charge_value' => 0,
        ];

        // Act
        $result = $calculator->calculate($novedad, $service);

        // Assert - should be zero (no charge)
        $this->assertEquals(0, $result);
    }

    /**
     * Test that calculate uses fixed charge type by default
     */
    public function test_calculate_uses_fixed_charge_type_by_default(): void
    {
        // Arrange
        $calculator = new CargoReconexionCalculator();

        // Mock the service
        $service = Mockery::mock(Service::class);

        // Mock the novedad with no charge type specified
        $novedad = Mockery::mock(BillingNovedad::class);
        $novedad->rule = [
            'charge_value' => 15.00,
        ];

        // Act
        $result = $calculator->calculate($novedad, $service);

        // Assert - should use fixed charge type
        $this->assertEquals(15.00, $result);
    }
}
