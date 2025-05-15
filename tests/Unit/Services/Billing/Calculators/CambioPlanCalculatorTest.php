<?php

namespace Tests\Unit\Services\Billing\Calculators;

use App\Models\BillingNovedad;
use App\Models\Services\Plan;
use App\Models\Services\Service;
use App\Services\Billing\Calculators\CambioPlanCalculator;
use Carbon\Carbon;
use Mockery;
use PHPUnit\Framework\TestCase;

class CambioPlanCalculatorTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    /**
     * Test that calculate returns positive value when new plan is more expensive
     */
    public function test_calculate_returns_positive_value_when_new_plan_is_more_expensive(): void
    {
        // Arrange
        $calculator = new CambioPlanCalculator();

        // Mock the old plan
        $oldPlan = Mockery::mock(Plan::class);
        $oldPlan->monthly_price = 50.00;

        // Mock the new plan
        $newPlan = Mockery::mock(Plan::class);
        $newPlan->monthly_price = 80.00;

        // Mock the Plan model's findOrFail method
        Plan::shouldReceive('findOrFail')
            ->with(1)
            ->andReturn($oldPlan);
        Plan::shouldReceive('findOrFail')
            ->with(2)
            ->andReturn($newPlan);

        // Mock the novedad
        $novedad = Mockery::mock(BillingNovedad::class);
        $novedad->rule = [
            'old_plan_id' => 1,
            'new_plan_id' => 2,
            'change_day' => 15, // Middle of the month
        ];
        $novedad->effective_period = '2023-05-01'; // May 2023

        // Mock the service
        $service = Mockery::mock(Service::class);
        $service->plan = $oldPlan;

        // Act
        $result = $calculator->calculate($novedad, $service);

        // Assert - should be positive (charge)
        $this->assertGreaterThan(0, $result);

        // In a 31-day month, with change on day 15, there are 17 days remaining (15-31)
        // Old plan: 50.00 / 31 * 17 = 27.42
        // New plan: 80.00 / 31 * 17 = 43.87
        // Difference: 43.87 - 27.42 = 16.45
        $this->assertEquals(16.45, round($result, 2));
    }

    /**
     * Test that calculate returns negative value when new plan is less expensive
     */
    public function test_calculate_returns_negative_value_when_new_plan_is_less_expensive(): void
    {
        // Arrange
        $calculator = new CambioPlanCalculator();

        // Mock the old plan
        $oldPlan = Mockery::mock(Plan::class);
        $oldPlan->monthly_price = 80.00;

        // Mock the new plan
        $newPlan = Mockery::mock(Plan::class);
        $newPlan->monthly_price = 50.00;

        // Mock the Plan model's findOrFail method
        Plan::shouldReceive('findOrFail')
            ->with(1)
            ->andReturn($oldPlan);
        Plan::shouldReceive('findOrFail')
            ->with(2)
            ->andReturn($newPlan);

        // Mock the novedad
        $novedad = Mockery::mock(BillingNovedad::class);
        $novedad->rule = [
            'old_plan_id' => 1,
            'new_plan_id' => 2,
            'change_day' => 15, // Middle of the month
        ];
        $novedad->effective_period = '2023-05-01'; // May 2023

        // Mock the service
        $service = Mockery::mock(Service::class);
        $service->plan = $oldPlan;

        // Act
        $result = $calculator->calculate($novedad, $service);

        // Assert - should be negative (discount)
        $this->assertLessThan(0, $result);

        // In a 31-day month, with change on day 15, there are 17 days remaining (15-31)
        // Old plan: 80.00 / 31 * 17 = 43.87
        // New plan: 50.00 / 31 * 17 = 27.42
        // Difference: 27.42 - 43.87 = -16.45
        $this->assertEquals(-16.45, round($result, 2));
    }

    /**
     * Test that calculate throws exception when plan IDs are missing
     */
    public function test_calculate_throws_exception_when_plan_ids_are_missing(): void
    {
        // Arrange
        $calculator = new CambioPlanCalculator();

        // Mock the novedad with missing plan IDs
        $novedad = Mockery::mock(BillingNovedad::class);
        $novedad->rule = [];
        $novedad->effective_period = '2023-05-01';

        // Mock the service
        $service = Mockery::mock(Service::class);

        // Assert & Act
        $this->expectException(\InvalidArgumentException::class);
        $calculator->calculate($novedad, $service);
    }
}
