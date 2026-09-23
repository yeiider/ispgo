<?php

namespace Tests\Feature;

use App\Models\Customers\Customer;
use App\Models\Customers\TaxDetail;
use App\Models\Invoice\Invoice;
use App\Models\Router;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Ispgo\Siigo\Jobs\CancelSiigoInvoice;
use Ispgo\Siigo\Jobs\DeleteSiigoInvoice;
use Ispgo\Siigo\SiigoClient;
use GuzzleHttp\Psr7\Response;
use Mockery;
use Tests\TestCase;

class SiigoInvoiceCancelDeleteTest extends TestCase
{
    use RefreshDatabase;

    public function test_cancel_siigo_invoice_annuls_draft_invoice()
    {
        $user = \App\Models\User::factory()->create();
        $router = Router::factory()->create();
        $customer = Customer::factory()->create(['router_id' => $router->id, 'date_of_birth' => '1990-01-01']);
        TaxDetail::factory()->create([
            'customer_id' => $customer->id,
            'enable_billing' => true,
        ]);

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
            'increment_id' => '0000000010',
            'additional_information' => [
                'siigo_invoice_id' => 'siigo-uuid-123',
            ],
        ]);

        $siigoMock = Mockery::mock(SiigoClient::class);
        
        // Mock getInvoiceByUuid returning draft status (no stamp)
        $siigoMock->shouldReceive('getInvoiceByUuid')
            ->with('siigo-uuid-123')
            ->once()
            ->andReturn(new Response(200, [], json_encode([
                'id' => 'siigo-uuid-123',
                'stamp' => ['status' => 'draft']
            ])));

        // Expect annulInvoice call
        $siigoMock->shouldReceive('annulInvoice')
            ->with('siigo-uuid-123')
            ->once()
            ->andReturn(new Response(200, [], json_encode(['status' => 'annulled'])));

        $job = new CancelSiigoInvoice($invoice, true);
        $job->handle($siigoMock);

        $invoice->refresh();
        $this->assertTrue($invoice->additional_information['siigo_annulled'] ?? false);
    }

    public function test_cancel_siigo_invoice_creates_credit_note_for_stamped_invoice()
    {
        $user = \App\Models\User::factory()->create();
        $router = Router::factory()->create();
        $customer = Customer::factory()->create(['router_id' => $router->id, 'date_of_birth' => '1990-01-01']);
        TaxDetail::factory()->create([
            'customer_id' => $customer->id,
            'enable_billing' => true,
        ]);

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
            'increment_id' => '0000000011',
            'additional_information' => [
                'siigo_invoice_id' => 'siigo-uuid-stamped',
            ],
        ]);

        $siigoMock = Mockery::mock(SiigoClient::class);
        
        // Mock getInvoiceByUuid returning stamped status
        $siigoMock->shouldReceive('getInvoiceByUuid')
            ->with('siigo-uuid-stamped')
            ->once()
            ->andReturn(new Response(200, [], json_encode([
                'id' => 'siigo-uuid-stamped',
                'stamp' => ['status' => 'stamped', 'cufe' => 'cufe-xyz-123']
            ])));

        // Expect createCreditNote and stampCreditNote
        $siigoMock->shouldReceive('createCreditNote')
            ->once()
            ->andReturn(new Response(201, [], json_encode([
                'id' => 'cn-uuid-789',
                'name' => 'NC-1',
                'number' => 1
            ])));

        $siigoMock->shouldReceive('stampCreditNote')
            ->with('cn-uuid-789')
            ->once()
            ->andReturn(new Response(200, [], json_encode(['status' => 'stamped'])));

        $job = new CancelSiigoInvoice($invoice, true);
        $job->handle($siigoMock);

        $invoice->refresh();
        $this->assertEquals('cn-uuid-789', $invoice->additional_information['siigo_credit_note_id'] ?? null);
    }

    public function test_delete_siigo_invoice_deletes_draft_invoice()
    {
        $user = \App\Models\User::factory()->create();
        $router = Router::factory()->create();
        $customer = Customer::factory()->create(['router_id' => $router->id, 'date_of_birth' => '1990-01-01']);
        TaxDetail::factory()->create([
            'customer_id' => $customer->id,
            'enable_billing' => true,
        ]);

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
            'increment_id' => '0000000012',
            'additional_information' => [
                'siigo_invoice_id' => 'siigo-uuid-draft',
            ],
        ]);

        $siigoMock = Mockery::mock(SiigoClient::class);
        
        // Mock getInvoiceByUuid returning draft status
        $siigoMock->shouldReceive('getInvoiceByUuid')
            ->with('siigo-uuid-draft')
            ->once()
            ->andReturn(new Response(200, [], json_encode([
                'id' => 'siigo-uuid-draft',
                'stamp' => ['status' => 'draft']
            ])));

        // Expect deleteInvoice call
        $siigoMock->shouldReceive('deleteInvoice')
            ->with('siigo-uuid-draft')
            ->once()
            ->andReturn(new Response(204, [], ''));

        $job = new DeleteSiigoInvoice($invoice, true);
        $job->handle($siigoMock);
    }
}
