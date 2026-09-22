<?php

namespace Tests\Feature;

use App\Models\Customers\Customer;
use App\Models\Customers\TaxDetail;
use App\Models\Invoice\Invoice;
use App\Models\Router;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Ispgo\Siigo\Jobs\CreateSiigoInvoice;
use Ispgo\Siigo\Jobs\PaySiigoInvoice;
use Ispgo\Siigo\SiigoClient;
use GuzzleHttp\Psr7\Response;
use Mockery;
use Tests\TestCase;

class SiigoInvoiceStampTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_siigo_invoice_mode_none_does_not_stamp()
    {
        $router = Router::factory()->create();
        $customer = Customer::factory()->create(['router_id' => $router->id]);
        TaxDetail::factory()->create([
            'customer_id' => $customer->id,
            'enable_billing' => true,
        ]);

        $invoice = Invoice::factory()->create([
            'customer_id' => $customer->id,
            'router_id' => $router->id,
        ]);

        // Config: stamp_invoice_trigger = none
        \App\Models\CoreConfigData::updateOrCreate(
            ['path' => 'siigo/invoices/stamp_invoice_trigger', 'scope_id' => $router->id],
            ['value' => 'none']
        );

        $siigoMock = Mockery::mock(SiigoClient::class);

        // Expect createCustomer sync
        $siigoMock->shouldReceive('getCustomer')
            ->andReturn(new Response(200, [], json_encode(['results' => [['id' => 'cust-123']]])));

        // Expect createInvoice with sendStamp = false
        $siigoMock->shouldReceive('createInvoice')
            ->once()
            ->with(Mockery::on(function ($payload) {
                return isset($payload['stamp']['send']) && $payload['stamp']['send'] === false;
            }))
            ->andReturn(new Response(201, [], json_encode(['id' => 'siigo-inv-123', 'name' => 'FV-1', 'number' => 1])));

        // stampInvoice should NOT be called
        $siigoMock->shouldNotReceive('stampInvoice');

        $job = new CreateSiigoInvoice($invoice, true);
        $job->handle($siigoMock);

        $invoice->refresh();
        $this->assertEquals('siigo-inv-123', $invoice->additional_information['siigo_invoice_id'] ?? null);
    }

    public function test_pay_siigo_invoice_mode_paid_only_stamps_on_payment()
    {
        $router = Router::factory()->create();
        $customer = Customer::factory()->create(['router_id' => $router->id]);
        TaxDetail::factory()->create([
            'customer_id' => $customer->id,
            'enable_billing' => true,
        ]);

        $invoice = Invoice::factory()->create([
            'customer_id' => $customer->id,
            'router_id' => $router->id,
            'total' => 50000,
            'additional_information' => [
                'siigo_invoice_id' => 'siigo-inv-456',
            ],
        ]);

        // Config: stamp_invoice_trigger = paid_only
        \App\Models\CoreConfigData::updateOrCreate(
            ['path' => 'siigo/invoices/stamp_invoice_trigger', 'scope_id' => $router->id],
            ['value' => 'paid_only']
        );

        $siigoMock = Mockery::mock(SiigoClient::class);

        // Expect stampInvoice to be called upon payment
        $siigoMock->shouldReceive('stampInvoice')
            ->with('siigo-inv-456')
            ->once()
            ->andReturn(new Response(200, [], json_encode(['status' => 'stamped'])));

        // Expect createVoucher
        $siigoMock->shouldReceive('createVoucher')
            ->once()
            ->andReturn(new Response(201, [], json_encode(['id' => 'vouch-123', 'name' => 'RC-1', 'number' => 1])));

        $job = new PaySiigoInvoice($invoice, 50000, true);
        $job->handle($siigoMock);

        $invoice->refresh();
        $this->assertTrue($invoice->additional_information['siigo_stamped'] ?? false);
        $this->assertEquals('vouch-123', $invoice->additional_information['siigo_voucher_id'] ?? null);
    }
}
