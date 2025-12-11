<?php

namespace App\Services\Billing;

use App\Models\Customers\Customer;
use App\Models\Invoice\Invoice;
use App\Settings\InvoiceProviderConfig;
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

            // Verificar si el cliente está al día con sus pagos
            $isCustomerUpToDate = $this->isCustomerUpToDate($customer);

            // Verificar configuraciones
            $skipIfSuspendedAndUnpaid = filter_var(
                InvoiceProviderConfig::skipInvoiceIfSuspendedAndUnpaid(),
                FILTER_VALIDATE_BOOLEAN,
                FILTER_NULL_ON_FAILURE
            );
            $enableRouterRental = filter_var(
                InvoiceProviderConfig::enableRouterRental(),
                FILTER_VALIDATE_BOOLEAN,
                FILTER_NULL_ON_FAILURE
            );
            $routerRentalAmount = (float) InvoiceProviderConfig::routerRentalAmount();
            $routerRentalName = InvoiceProviderConfig::routerRentalName() ?: 'Arrendamiento de Router';

            DB::transaction(function () use (
                $invoice,
                $services,
                $skipIfSuspendedAndUnpaid,
                $enableRouterRental,
                $routerRentalAmount,
                $routerRentalName,
                $isCustomerUpToDate
            ) {
                $hasServiceItems = false;
                $hasSkippedServices = false;

                foreach ($services as $service) {
                    // Verificar si debemos omitir la factura del servicio
                    $shouldSkipService = false;

                    if ($skipIfSuspendedAndUnpaid) {
                        // Verificar si el servicio está suspendido y tiene facturas sin pagar
                        $isSuspended = $service->service_status === 'suspended';
                        $hasUnpaidInvoices = $this->serviceHasUnpaidInvoices($service);

                        if ($isSuspended && $hasUnpaidInvoices) {
                            $shouldSkipService = true;
                            $hasSkippedServices = true;
                            Log::info("Servicio {$service->id} omitido: está suspendido y tiene facturas sin pagar", [
                                'service_id' => $service->id,
                                'customer_id' => $service->customer_id,
                            ]);
                        }
                    }

                    // Si no debemos omitir el servicio, crear el item
                    if (!$shouldSkipService) {
                        $servicePrice = $service->plan->monthly_price;

                        // Si el arrendamiento está activo y el cliente está al día, descontar el arrendo del servicio
                        if ($enableRouterRental && $routerRentalAmount > 0 && $isCustomerUpToDate) {
                            $servicePrice = max(0, $servicePrice - $routerRentalAmount);
                        }

                        // Solo crear el item si el precio es mayor a 0
                        if ($servicePrice > 0) {
                            $item = $invoice->items()->create([
                                'description' => "Suscripción {$service->plan->name}",
                                'invoice_id' => $invoice->id,
                                'unit_price' => $servicePrice,
                                'service_id' => $service->id,
                                'quantity' => 1,
                                'subtotal' => $servicePrice,
                            ]);

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

                            $hasServiceItems = true;
                        }
                    }
                }

                // Agregar el item de arrendamiento de router si está activo
                if ($enableRouterRental && $routerRentalAmount > 0) {
                    // Lógica para agregar el arrendo:
                    // 1. Si el cliente está al día: siempre agregar el arrendo (ya fue descontado del servicio)
                    // 2. Si el cliente NO está al día pero se omitieron servicios: agregar el arrendo (solo cobrar arrendo)
                    // 3. Si el cliente NO está al día y hay items de servicio: NO agregar el arrendo (cobrar servicio completo + arrendo no aplica)
                    $shouldAddRental = false;

                    if ($isCustomerUpToDate) {
                        // Cliente al día: siempre agregar el arrendo (ya fue descontado del servicio)
                        $shouldAddRental = true;
                    } elseif ($hasSkippedServices && !$hasServiceItems) {
                        // Se omitieron servicios y no hay items de servicio: solo cobrar el arrendo
                        $shouldAddRental = true;
                    }

                    if ($shouldAddRental) {
                        $rentalItem = $invoice->items()->create([
                            'description' => $routerRentalName,
                            'invoice_id' => $invoice->id,
                            'unit_price' => $routerRentalAmount,
                            'service_id' => null,
                            'quantity' => 1,
                            'subtotal' => $routerRentalAmount,
                        ]);

                        $invoice->adjustments()->create([
                            'kind' => 'charge',
                            'amount' => $rentalItem->subtotal,
                            'source_type' => 'router_rental',
                            'source_id' => null,
                            'label' => "Ajuste: {$routerRentalName}",
                            'metadata' => [
                                'type' => 'router_rental',
                                'invoice_item_id' => $rentalItem->id,
                            ],
                            'created_by' => auth()->id(),
                        ]);
                    }
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

    /**
     * Verifica si un cliente está al día con sus pagos
     * (no tiene facturas con saldo pendiente)
     */
    private function isCustomerUpToDate(Customer $customer): bool
    {
        return !$customer->invoices()
            ->where('outstanding_balance', '>', 0)
            ->whereIn('status', ['unpaid', 'overdue'])
            ->exists();
    }

    /**
     * Verifica si la última factura del servicio está sin pagar
     */
    private function serviceHasUnpaidInvoices($service): bool
    {
        $lastInvoice = $service->invoices()
            ->orderBy('created_at', 'desc')
            ->orderBy('id', 'desc')
            ->first();

        if (!$lastInvoice) {
            return false;
        }

        return $lastInvoice->outstanding_balance > 0 
            && in_array($lastInvoice->status, ['unpaid', 'overdue']);
    }
}
