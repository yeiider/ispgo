<?php

namespace Tests\Unit\Services\Billing;

use App\Models\Customers\Customer;
use App\Models\Invoice\Invoice;
use App\Models\Services\Plan;
use App\Models\Services\Service;
use App\Services\Billing\CustomerBillingService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Mockery;
use PHPUnit\Framework\TestCase;

class CustomerBillingServiceTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    /**
     * Test that generateForPeriod returns null when customer has no active services
     */
    public function test_generate_for_period_returns_null_when_no_active_services(): void
    {
        // Arrange
        $customer = Mockery::mock(Customer::class);
        $activeServices = Mockery::mock('Illuminate\Database\Eloquent\Builder');
        $customer->shouldReceive('activeServices')->once()->andReturn($activeServices);
        $activeServices->shouldReceive('get')->once()->andReturn(new Collection());

        $period = Carbon::now();
        $service = new CustomerBillingService();

        // Act
        $result = $service->generateForPeriod($customer, $period);

        // Assert
        $this->assertNull($result);
    }

    /**
     * Test that generateForPeriod creates invoice with items for each service
     */
    public function test_generate_for_period_creates_invoice_with_items(): void
    {
        // Arrange
        Event::fake();
        DB::shouldReceive('transaction')->once()->andReturnUsing(function ($callback) {
            $callback();
        });

        $period = Carbon::now();
        $periodKey = $period->format('Y-m');

        // Mock Plan
        $plan = Mockery::mock(Plan::class);
        $plan->name = 'Test Plan';
        $plan->monthly_price = 100.00;

        // Mock Service
        $service = Mockery::mock(Service::class);
        $service->shouldReceive('getAttribute')->with('plan')->andReturn($plan);
        $service->id = 1;
        $service->plan = $plan;

        // Mock Invoice Items
        $items = Mockery::mock(HasMany::class);
        $item = (object)[
            'id' => 1,
            'description' => "Suscripción Test Plan",
            'subtotal' => 100.00
        ];
        $items->shouldReceive('create')->once()->andReturn($item);

        // Mock Invoice Adjustments
        $adjustments = Mockery::mock(HasMany::class);
        $adjustments->shouldReceive('create')->once();

        // Mock Invoice
        $invoice = Mockery::mock(Invoice::class);
        $invoice->shouldReceive('items')->once()->andReturn($items);
        $invoice->shouldReceive('adjustments')->once()->andReturn($adjustments);
        $invoice->shouldReceive('recalcTotals')->once();
        $invoice->shouldReceive('update')->once()->with(['state' => 'building']);
        $invoice->id = 1;

        // Mock Customer
        $customer = Mockery::mock(Customer::class);
        $activeServices = Mockery::mock('Illuminate\Database\Eloquent\Builder');
        $customer->shouldReceive('activeServices')->once()->andReturn($activeServices);
        $activeServices->shouldReceive('get')->once()->andReturn(new Collection([$service]));
        $customer->shouldReceive('openDraftInvoice')->with($periodKey)->once()->andReturn($invoice);
        $customer->id = 1;

        $billingService = new CustomerBillingService();

        // Act
        $result = $billingService->generateForPeriod($customer, $period);

        // Assert
        $this->assertSame($invoice, $result);
    }

    /**
     * Test that generateForPeriod throws exception when an error occurs
     */
    public function test_generate_for_period_throws_exception_on_error(): void
    {
        // Arrange
        $customer = Mockery::mock(Customer::class);
        $activeServices = Mockery::mock('Illuminate\Database\Eloquent\Builder');
        $customer->shouldReceive('activeServices')->once()->andReturn($activeServices);
        $activeServices->shouldReceive('get')->once()->andThrow(new \Exception('Test exception'));
        $customer->id = 1;

        $period = Carbon::now();
        $service = new CustomerBillingService();

        // Assert
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("No se pudo generar la factura para el cliente 1.");

        // Act
        $service->generateForPeriod($customer, $period);
    }
}
