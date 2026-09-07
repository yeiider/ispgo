<?php

namespace Tests\Feature;

use App\Events\InvoiceDiscountApplied;
use App\Models\Customers\Customer;
use App\Models\Invoice\Invoice;
use App\Models\InvoiceAdjustment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Queue;
use Ispgo\Siigo\Jobs\CreateSiigoDiscountCreditNote;
use Tests\TestCase;

class InvoiceDiscountTest extends TestCase
{
    use RefreshDatabase;

    public function test_apply_discount_creates_invoice_adjustment_and_fires_event()
    {
        Event::fake([InvoiceDiscountApplied::class, \App\Events\InvoiceCreated::class]);

        $user = User::factory()->create();
        $router = \App\Models\Router::factory()->create();
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
            'subtotal' => 100000,
            'tax' => 19000,
            'total' => 119000,
            'amount' => 0,
            'outstanding_balance' => 119000,
            'status' => 'unpaid',
            'increment_id' => '0000000001',
        ]);

        $invoice = $invoice->fresh();
        $this->actingAs($user);

        $invoice->applyDiscountWithoutTax(10000, 'Descuento comercial por fidelización');

        $this->assertEquals(90000, $invoice->subtotal);
        $this->assertEquals(109000, $invoice->total);

        $this->assertDatabaseHas('invoice_adjustments', [
            'invoice_id' => $invoice->id,
            'kind' => 'discount',
            'amount' => 10000.00,
            'label' => 'Descuento comercial por fidelización',
        ]);

        Event::assertDispatched(InvoiceDiscountApplied::class, function ($event) use ($invoice) {
            return $event->invoice->id === $invoice->id
                && $event->discountAmount === 10000.0
                && $event->description === 'Descuento comercial por fidelización';
        });
    }

    public function test_siigo_discount_credit_note_job_is_dispatched_when_invoice_has_siigo_id()
    {
        Queue::fake();
        Event::fake([\App\Events\InvoiceCreated::class]);

        $user = User::factory()->create();
        $router = \App\Models\Router::factory()->create();
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
            'subtotal' => 100000,
            'tax' => 19000,
            'total' => 119000,
            'amount' => 0,
            'outstanding_balance' => 119000,
            'status' => 'unpaid',
            'increment_id' => '0000000002',
            'additional_information' => [
                'siigo_invoice_id' => 'siigo-uuid-12345',
            ],
        ]);

        // Mock config for Siigo enabled
        \App\Models\CoreConfigData::updateOrCreate(['path' => 'siigo/general/enabled', 'scope_id' => 0], ['value' => '1']);
        \App\Models\CoreConfigData::updateOrCreate(['path' => 'siigo/invoices/sync_invoice', 'scope_id' => 0], ['value' => '1']);

        // Attach tax details with billing enabled
        $customer->taxDetails()->create([
            'enable_billing' => true,
            'taxpayer_type' => 'person',
            'fiscal_regime' => 'R-99-PN',
            'business_name' => 'Persona Natural Test',
            'tax_identification_type' => 'CC',
            'tax_identification_number' => '123456789',
        ]);

        $listener = new \Ispgo\Siigo\Listeners\SyncInvoice();
        $event = new InvoiceDiscountApplied($invoice, 15000, 'Rebaja especial');

        $listener->onDiscountApplied($event);

        Queue::assertPushed(CreateSiigoDiscountCreditNote::class);
    }
}
