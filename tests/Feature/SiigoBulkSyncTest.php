<?php

namespace Tests\Feature;

use App\Models\Customers\Customer;
use App\Models\Invoice\Invoice;
use App\Models\Router;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Ispgo\Siigo\Jobs\CreateSiigoCustomer;
use Ispgo\Siigo\Jobs\CreateSiigoInvoice;
use Ispgo\Siigo\Settings\ConfigProviderSiigo;
use Tests\TestCase;

class SiigoBulkSyncTest extends TestCase
{
    use RefreshDatabase;

    public function test_bulk_sync_customers_dispatches_jobs_when_siigo_enabled()
    {
        Queue::fake();

        $user = User::factory()->create();
        $router = Router::factory()->create();
        $customer1 = Customer::factory()->create([
            'router_id' => $router->id,
            'date_of_birth' => now()->subYears(25)->format('Y-m-d'),
        ]);
        $customer2 = Customer::factory()->create([
            'router_id' => $router->id,
            'date_of_birth' => now()->subYears(30)->format('Y-m-d'),
        ]);

        // Simular que Siigo está habilitado para el router
        \App\Models\CoreConfigData::updateOrCreate(
            ['path' => 'siigo/general/enabled', 'scope_id' => $router->id],
            ['value' => '1']
        );

        \Laravel\Passport\Passport::actingAs($user);

        $mutation = '
            mutation BulkSyncCustomers($customer_ids: [ID!]!) {
                bulkSyncCustomersToSiigo(customer_ids: $customer_ids) {
                    success
                    message
                }
            }
        ';

        $response = $this->postJson('/graphql', [
            'query' => $mutation,
            'variables' => [
                'customer_ids' => [$customer1->id, $customer2->id],
            ],
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('data.bulkSyncCustomersToSiigo.success', true);

        Queue::assertPushed(CreateSiigoCustomer::class, 2);
    }

    public function test_bulk_sync_invoices_dispatches_jobs_when_siigo_enabled()
    {
        Queue::fake();

        $user = User::factory()->create();
        $router = Router::factory()->create();
        $customer = Customer::factory()->create([
            'router_id' => $router->id,
            'date_of_birth' => now()->subYears(25)->format('Y-m-d'),
        ]);

        $invoice = Invoice::create([
            'user_id' => $user->id,
            'customer_id' => $customer->id,
            'router_id' => $router->id,
            'issue_date' => now(),
            'due_date' => now()->addDays(30),
            'billing_period' => now()->format('Y-m'),
            'subtotal' => 50000,
            'tax' => 9500,
            'total' => 59500,
            'amount' => 0,
            'outstanding_balance' => 59500,
            'status' => 'unpaid',
            'increment_id' => '0000000002',
        ]);

        \App\Models\CoreConfigData::updateOrCreate(
            ['path' => 'siigo/general/enabled', 'scope_id' => $router->id],
            ['value' => '1']
        );

        \Laravel\Passport\Passport::actingAs($user);

        $mutation = '
            mutation BulkSyncInvoices($invoice_ids: [ID!]!) {
                bulkSyncInvoicesToSiigo(invoice_ids: $invoice_ids) {
                    success
                    message
                }
            }
        ';

        $response = $this->postJson('/graphql', [
            'query' => $mutation,
            'variables' => [
                'invoice_ids' => [$invoice->id],
            ],
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('data.bulkSyncInvoicesToSiigo.success', true);

        Queue::assertPushed(CreateSiigoInvoice::class, 1);
    }

    public function test_bulk_sync_returns_error_when_siigo_disabled()
    {
        Queue::fake();

        $user = User::factory()->create();
        $router = Router::factory()->create();
        $customer = Customer::factory()->create([
            'router_id' => $router->id,
            'date_of_birth' => now()->subYears(25)->format('Y-m-d'),
        ]);

        \App\Models\CoreConfigData::updateOrCreate(
            ['path' => 'siigo/general/enabled', 'scope_id' => $router->id],
            ['value' => '0']
        );

        \Laravel\Passport\Passport::actingAs($user);

        $mutation = '
            mutation BulkSyncCustomers($customer_ids: [ID!]!) {
                bulkSyncCustomersToSiigo(customer_ids: $customer_ids) {
                    success
                    message
                }
            }
        ';

        $response = $this->postJson('/graphql', [
            'query' => $mutation,
            'variables' => [
                'customer_ids' => [$customer->id],
            ],
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('data.bulkSyncCustomersToSiigo.success', false);

        Queue::assertNothingPushed();
    }
}
