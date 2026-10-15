<?php

namespace Tests\Feature;

use App\Models\Customers\Customer;
use App\Models\Customers\TaxDetail;
use App\Models\Invoice\Invoice;
use App\Models\Router;
use App\Models\User;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Ispgo\Siigo\Helpers\SiigoHelper;
use Ispgo\Siigo\Jobs\CreateSiigoInvoice;
use Ispgo\Siigo\SiigoClient;
use Laravel\Passport\Passport;
use Mockery;
use Tests\TestCase;

class SiigoPastDateInvoiceSyncTest extends TestCase
{
    use RefreshDatabase;

    public function test_past_date_invoice_payload_without_setting_keeps_original_date(): void
    {
        $router = Router::factory()->create();
        $customer = Customer::factory()->create(['router_id' => $router->id, 'date_of_birth' => '1990-01-01']);
        $user = User::factory()->create();

        $pastDate = now()->subDays(12);
        $invoice = Invoice::create([
            'user_id' => $user->id,
            'customer_id' => $customer->id,
            'router_id' => $router->id,
            'issue_date' => $pastDate,
            'due_date' => $pastDate->copy()->addDays(5),
            'billing_period' => $pastDate->format('Y-m'),
            'subtotal' => 50000,
            'tax' => 0,
            'total' => 50000,
            'outstanding_balance' => 50000,
        ]);

        // Setting is false / not configured
        $payload = SiigoHelper::buildInvoicePayload($invoice);

        $this->assertEquals($pastDate->format('Y-m-d'), $payload['date']);
        $this->assertEquals($pastDate->copy()->addDays(5)->format('Y-m-d'), $payload['payments'][0]['due_date']);
        $this->assertStringNotContainsString('Fecha original de emisión', $payload['observations']);
    }

    public function test_past_date_invoice_payload_with_setting_uses_current_date_and_adds_traceability(): void
    {
        $router = Router::factory()->create();
        $customer = Customer::factory()->create(['router_id' => $router->id, 'date_of_birth' => '1990-01-01']);
        $user = User::factory()->create();

        $pastDate = now()->subDays(12);
        $pastDueDate = $pastDate->copy()->addDays(5); // also in the past
        $invoice = Invoice::create([
            'user_id' => $user->id,
            'customer_id' => $customer->id,
            'router_id' => $router->id,
            'issue_date' => $pastDate,
            'due_date' => $pastDueDate,
            'billing_period' => $pastDate->format('Y-m'),
            'subtotal' => 50000,
            'tax' => 0,
            'total' => 50000,
            'outstanding_balance' => 50000,
        ]);

        // Enable allow_sync_past_date_invoices
        \App\Models\CoreConfigData::updateOrCreate(
            ['path' => 'siigo/invoices/allow_sync_past_date_invoices', 'scope_id' => $router->id],
            ['value' => '1']
        );

        $payload = SiigoHelper::buildInvoicePayload($invoice);

        $today = now()->format('Y-m-d');
        $this->assertEquals($today, $payload['date']);
        // Due date must be adjusted so it is at least today
        $this->assertEquals($today, $payload['payments'][0]['due_date']);
        $this->assertGreaterThanOrEqual($payload['date'], $payload['payments'][0]['due_date']);
        // Traceability in observations
        $this->assertStringContainsString('Fecha original de emisión: ' . $pastDate->format('Y-m-d'), $payload['observations']);
        $this->assertStringContainsString('Periodo: ' . $pastDate->format('Y-m'), $payload['observations']);
    }

    public function test_create_siigo_invoice_throws_friendly_error_on_invalid_date_response(): void
    {
        $router = Router::factory()->create();
        $customer = Customer::factory()->create(['router_id' => $router->id, 'date_of_birth' => '1990-01-01']);
        TaxDetail::factory()->create([
            'customer_id' => $customer->id,
            'enable_billing' => true,
        ]);
        $user = User::factory()->create();

        $pastDate = now()->subDays(15);
        $invoice = Invoice::create([
            'user_id' => $user->id,
            'customer_id' => $customer->id,
            'router_id' => $router->id,
            'issue_date' => $pastDate,
            'due_date' => $pastDate->copy()->addDays(5),
            'billing_period' => $pastDate->format('Y-m'),
            'subtotal' => 50000,
            'tax' => 0,
            'total' => 50000,
            'outstanding_balance' => 50000,
        ]);

        $siigoMock = Mockery::mock(SiigoClient::class);
        $siigoMock->shouldReceive('getCustomer')
            ->andReturn(new Response(200, [], json_encode(['results' => [['id' => 'cust-123']]])));

        $errorBody = json_encode([
            'Status' => 400,
            'Errors' => [
                [
                    'Code' => 'invalid_date',
                    'Message' => 'Invalid date: ' . $pastDate->format('Y-m-d'),
                    'Params' => ['date'],
                    'Detail' => 'Check that the date is valid',
                ]
            ]
        ]);
        $clientException = new ClientException(
            "Client error: POST https://api.siigo.com/v1/invoices resulted in a 400 Bad Request response: " . $errorBody,
            new Request('POST', 'https://api.siigo.com/v1/invoices'),
            new Response(400, [], $errorBody)
        );

        $siigoMock->shouldReceive('createInvoice')
            ->once()
            ->andThrow($clientException);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Siigo no puede sincronizar una factura de fechas anteriores.');

        $job = new CreateSiigoInvoice($invoice, true);
        $job->handle($siigoMock);
    }

    public function test_api_sync_endpoint_returns_friendly_error_message(): void
    {
        $router = Router::factory()->create();
        $customer = Customer::factory()->create(['router_id' => $router->id, 'date_of_birth' => '1990-01-01']);
        TaxDetail::factory()->create([
            'customer_id' => $customer->id,
            'enable_billing' => true,
        ]);
        $user = User::factory()->create();

        $pastDate = now()->subDays(15);
        $invoice = Invoice::create([
            'user_id' => $user->id,
            'customer_id' => $customer->id,
            'router_id' => $router->id,
            'issue_date' => $pastDate,
            'due_date' => $pastDate->copy()->addDays(5),
            'billing_period' => $pastDate->format('Y-m'),
            'subtotal' => 50000,
            'tax' => 0,
            'total' => 50000,
            'outstanding_balance' => 50000,
        ]);

        \App\Models\CoreConfigData::updateOrCreate(
            ['path' => 'siigo/general/enabled', 'scope_id' => 0],
            ['value' => '1']
        );

        $siigoMock = Mockery::mock(SiigoClient::class);
        $siigoMock->shouldReceive('getCustomer')
            ->andReturn(new Response(200, [], json_encode(['results' => [['id' => 'cust-123']]])));

        $errorBody = json_encode([
            'Status' => 400,
            'Errors' => [
                [
                    'Code' => 'invalid_date',
                    'Message' => 'Invalid date: ' . $pastDate->format('Y-m-d'),
                    'Params' => ['date'],
                    'Detail' => 'Check that the date is valid',
                ]
            ]
        ]);
        $clientException = new ClientException(
            "Client error: POST https://api.siigo.com/v1/invoices resulted in a 400 Bad Request response: " . $errorBody,
            new Request('POST', 'https://api.siigo.com/v1/invoices'),
            new Response(400, [], $errorBody)
        );

        $siigoMock->shouldReceive('createInvoice')
            ->once()
            ->andThrow($clientException);

        $this->app->instance(SiigoClient::class, $siigoMock);

        Passport::actingAs($user);

        $response = $this->postJson("/api/v1/invoices/{$invoice->id}/sync-siigo");

        $response->assertStatus(400)
            ->assertJson([
                'error' => 'Siigo no puede sincronizar una factura de fechas anteriores.'
            ]);
    }
}
