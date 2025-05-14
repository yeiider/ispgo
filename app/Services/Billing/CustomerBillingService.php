<?php

namespace App\Services\Billing;

use App\Models\Customers\Customer;
use App\Models\Invoice\Invoice;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;

class CustomerBillingService
{
    public function generateForPeriod(Customer $customer, Carbon $period): ?Invoice
    {
        try {
            // 1. Validar que tenga al menos un servicio facturable
            $services = $customer->activeServices()->get();

            if ($services->isEmpty()) {
                return null;
            }

            $periodKey = $period->format('Y-m');
            $invoice = $customer->openDraftInvoice($periodKey);

            DB::transaction(function () use ($invoice, $services) {
                foreach ($services as $service) {
                    // Crear el item de la factura
                    $item = $invoice->items()->create(
                        [
                            'description' => "Suscripción {$service->plan->name}",
                            'invoice_id' => $invoice->id,
                            'unit_price' => $service->plan->monthly_price,
                            'service_id' => $service->id,
                            'quantity' => 1,
                            'subtotal' => $service->plan->monthly_price,
                        ]
                    );

                    $invoice->adjustments()->create([
                        'kind' => 'charge',
                        'amount' => $item->subtotal,
                        'source_type' => get_class($service),
                        'source_id' => $service->id,
                        'label' => "Ajuste: {$item->description}",
                        'metadata' => [
                            'service_id' => $service->id,
                            'plan_name' => $service->plan->name,
                            'invoice_item_id' => $item->id,
                        ],
                        'created_by' => auth()->id(),
                    ]);
                }

                $invoice->recalcTotals();
                $invoice->update(['state' => 'building']);
            });

            event(new \App\Events\InvoiceItemsCreated($invoice));

            return $invoice;

        } catch (Exception $e) {
            // Registrar el error en el log de Laravel
            Log::error('Error al generar la factura para el cliente.', [
                'customer_id' => $customer->id,
                'period' => $period->toDateString(),
                'error_message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            // Retornar `null` o volver a lanzar la excepción según la necesidad del flujo
            throw new Exception("No se pudo generar la factura para el cliente {$customer->id}.", 0, $e);
        }
    }
}
