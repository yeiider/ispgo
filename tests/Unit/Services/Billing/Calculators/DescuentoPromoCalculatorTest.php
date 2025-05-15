<?php

namespace Tests\Unit\Services\Billing\Calculators;

use App\Models\BillingNovedad;
use App\Models\Services\Plan;
use App\Models\Services\Service;
use App\Services\Billing\Calculators\DescuentoPromoCalculator;
use Carbon\Carbon;
use Mockery;
use PHPUnit\Framework\TestCase;

class DescuentoPromoCalculatorTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    /**
     * Test that calculate returns correct percentage discount
     */
    public function test_calculate_returns_correct_percentage_discount(): void
    {
        // Arrange
        $calculator = new DescuentoPromoCalculator();

        // Mock the plan
        $plan = Mockery::mock(Plan::class);
        $plan->monthly_price = 100.00;

        // Mock the service
        $service = Mockery::mock(Service::class);
        $service->plan = $plan;

        // Mock the novedad with percentage discount
        $novedad = Mockery::mock(BillingNovedad::class);
        $novedad->rule = [
            'discount_type' => 'percentage',
            'discount_value' => 25, // 25% discount
        ];

        // Act
        $result = $calculator->calculate($novedad, $service);

        // Assert - should be negative (discount)
        $this->assertLessThan(0, $result);
        $this->assertEquals(-25.00, $result); // 25% of 100.00 = 25.00
    }

    /**
     * Test that calculate returns correct fixed discount
     */
    public function test_calculate_returns_correct_fixed_discount(): void
    {
        // Arrange
        $calculator = new DescuentoPromoCalculator();

        // Mock the plan
        $plan = Mockery::mock(Plan::class);
        $plan->monthly_price = 100.00;

        // Mock the service
        $service = Mockery::mock(Service::class);
        $service->plan = $plan;

        // Mock the novedad with fixed discount
        $novedad = Mockery::mock(BillingNovedad::class);
        $novedad->rule = [
            'discount_type' => 'fixed',
            'discount_value' => 30, // $30 discount
        ];

        // Act
        $result = $calculator->calculate($novedad, $service);

        // Assert - should be negative (discount)
        $this->assertLessThan(0, $result);
        $this->assertEquals(-30.00, $result);
    }

    /**
     * Test that calculate limits fixed discount to monthly price
     */
    public function test_calculate_limits_fixed_discount_to_monthly_price(): void
    {
        // Arrange
        $calculator = new DescuentoPromoCalculator();

        // Mock the plan
        $plan = Mockery::mock(Plan::class);
        $plan->monthly_price = 100.00;

        // Mock the service
        $service = Mockery::mock(Service::class);
        $service->plan = $plan;

        // Mock the novedad with fixed discount larger than monthly price
        $novedad = Mockery::mock(BillingNovedad::class);
        $novedad->rule = [
            'discount_type' => 'fixed',
            'discount_value' => 150, // $150 discount (more than monthly price)
        ];

        // Act
        $result = $calculator->calculate($novedad, $service);

        // Assert - should be limited to monthly price
        $this->assertLessThan(0, $result);
        $this->assertEquals(-100.00, $result); // Limited to $100
    }

    /**
     * Test that calculate respects time limits
     */
    public function test_calculate_respects_time_limits(): void
    {
        // Arrange
        $calculator = new DescuentoPromoCalculator();

        // Mock the plan
        $plan = Mockery::mock(Plan::class);
        $plan->monthly_price = 100.00;

        // Mock the service
        $service = Mockery::mock(Service::class);
        $service->plan = $plan;

        // Mock Carbon::now() to return a fixed date
        Carbon::setTestNow(Carbon::parse('2023-05-15'));

        // Mock the novedad with time limits
        $novedad = Mockery::mock(BillingNovedad::class);
        $novedad->rule = [
            'discount_type' => 'percentage',
            'discount_value' => 25,
            'start_date' => '2023-05-01',
            'end_date' => '2023-05-31',
        ];

        // Act
        $result = $calculator->calculate($novedad, $service);

        // Assert - should apply discount (within time limits)
        $this->assertLessThan(0, $result);
        $this->assertEquals(-25.00, $result);

        // Now test with a date outside the time limits
        Carbon::setTestNow(Carbon::parse('2023-06-15'));
        $result = $calculator->calculate($novedad, $service);

        // Assert - should not apply discount (outside time limits)
        $this->assertEquals(0, $result);

        // Reset the mock
        Carbon::setTestNow();
    }

    /**
     * Test that calculate returns zero for zero discount value
     */
    public function test_calculate_returns_zero_for_zero_discount_value(): void
    {
        // Arrange
        $calculator = new DescuentoPromoCalculator();

        // Mock the service
        $service = Mockery::mock(Service::class);

        // Mock the novedad with zero discount
        $novedad = Mockery::mock(BillingNovedad::class);
        $novedad->rule = [
            'discount_type' => 'percentage',
            'discount_value' => 0,
        ];

        // Act
        $result = $calculator->calculate($novedad, $service);

        // Assert - should be zero (no discount)
        $this->assertEquals(0, $result);
    }
}
