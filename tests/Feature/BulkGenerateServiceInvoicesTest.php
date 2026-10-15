<?php

namespace Tests\Feature;

use App\Events\FinalizeInvoice;
use App\Jobs\GenerateServiceInvoiceJob;
use App\Models\Customers\Customer;
use App\Models\Invoice\Invoice;
use App\Models\Router;
use App\Models\Services\Plan;
use App\Models\Services\Service;
use App\Models\User;
use App\Services\Billing\CustomerBillingService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Queue;
use Laravel\Passport\Passport;
use Mockery;
use Tests\TestCase;

class BulkGenerateServiceInvoicesTest extends TestCase
{
    use RefreshDatabase;

    public function test_generate_services_invoices_mutation_dispatches_jobs_to_redis_queue(): void
    {
        Queue::fake();

        $user = User::factory()->create();
        $router = Router::factory()->create();
        $customer = Customer::factory()->create([
            'router_id' => $router->id,
            'date_of_birth' => now()->subYears(25)->format('Y-m-d'),
        ]);

        $plan = Plan::factory()->create();

        $service1 = Service::factory()->create([
            'router_id' => $router->id,
            'customer_id' => $customer->id,
            'plan_id' => $plan->id,
            'service_status' => 'active',
        ]);

        $service2 = Service::factory()->create([
            'router_id' => $router->id,
            'customer_id' => $customer->id,
            'plan_id' => $plan->id,
            'service_status' => 'active',
        ]);

        Passport::actingAs($user);

        $mutation = '
            mutation GenerateInvoices($ids: [ID!]!) {
                generateServicesInvoices(service_ids: $ids) {
                    success
                    message
                }
            }
        ';

        $response = $this->postJson('/graphql', [
            'query' => $mutation,
            'variables' => [
                'ids' => [$service1->id, $service2->id],
            ],
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('data.generateServicesInvoices.success', true);

        Queue::assertPushedOn('redis', GenerateServiceInvoiceJob::class);
        Queue::assertPushed(GenerateServiceInvoiceJob::class, 2);
    }

    public function test_generate_services_invoices_returns_error_when_no_ids(): void
    {
        $user = User::factory()->create();
        Passport::actingAs($user);

        $mutation = '
            mutation GenerateInvoices($ids: [ID!]!) {
                generateServicesInvoices(service_ids: $ids) {
                    success
                    message
                }
            }
        ';

        $response = $this->postJson('/graphql', [
            'query' => $mutation,
            'variables' => [
                'ids' => [],
            ],
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('data.generateServicesInvoices.success', false);
    }

    public function test_job_skips_when_service_is_free_or_inactive(): void
    {
        Event::fake();

        $router = Router::factory()->create();
        $customer = Customer::factory()->create([
            'router_id' => $router->id,
            'date_of_birth' => now()->subYears(25)->format('Y-m-d'),
        ]);
        $plan = Plan::factory()->create();

        $service = Service::factory()->create([
            'router_id' => $router->id,
            'customer_id' => $customer->id,
            'plan_id' => $plan->id,
            'service_status' => 'free',
        ]);

        $billingService = Mockery::mock(CustomerBillingService::class);
        $billingService->shouldNotReceive('generateForPeriod');

        $job = new GenerateServiceInvoiceJob($service->id);
        $job->handle($billingService);

        Event::assertNotDispatched(FinalizeInvoice::class);
    }

    public function test_job_generates_invoice_and_dispatches_finalize_event_for_active_service(): void
    {
        Event::fake([FinalizeInvoice::class]);

        $router = Router::factory()->create();
        $customer = Customer::factory()->create([
            'router_id' => $router->id,
            'date_of_birth' => now()->subYears(25)->format('Y-m-d'),
        ]);
        $plan = Plan::factory()->create();

        $service = Service::factory()->create([
            'router_id' => $router->id,
            'customer_id' => $customer->id,
            'plan_id' => $plan->id,
            'service_status' => 'active',
        ]);

        $user = User::factory()->create();

        $invoice = Invoice::create([
            'user_id' => $user->id,
            'customer_id' => $customer->id,
            'router_id' => $router->id,
            'issue_date' => now(),
            'due_date' => now()->addDays(30),
            'billing_period' => now()->format('Y-m'),
            'subtotal' => 50000,
            'tax' => 0,
            'total' => 50000,
            'amount' => 0,
            'outstanding_balance' => 50000,
            'status' => 'unpaid',
            'increment_id' => '0000000001',
        ]);

        $billingService = Mockery::mock(CustomerBillingService::class);
        $billingService->shouldReceive('generateForPeriod')
            ->once()
            ->with(Mockery::on(fn($c) => $c->id === $customer->id), Mockery::any(), $service->id)
            ->andReturn($invoice);

        $job = new GenerateServiceInvoiceJob($service->id);
        $job->handle($billingService);

        Event::assertDispatched(FinalizeInvoice::class, function ($event) use ($invoice) {
            return $event->invoice->id === $invoice->id;
        });

        $this->assertEquals($service->id, $invoice->fresh()->service_id);
    }
}
