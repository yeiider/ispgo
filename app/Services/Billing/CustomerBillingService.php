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

            // Verificar configuraciones ANTES de crear el draft
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

            // Verificar si el cliente está al día con sus pagos
            $isCustomerUpToDate = $this->isCustomerUpToDate($customer);

            // Verificar si TODOS los servicios están suspendidos
            $allServicesSuspended = $this->allServicesSuspended($services);

            // Verificar si la última factura del cliente está sin pagar
            $lastInvoiceUnpaid = $this->customerLastInvoiceUnpaid($customer);

            // VALIDACIÓN PRINCIPAL: Si está activa la opción de omitir factura
            // y TODOS los servicios están suspendidos y la última factura está sin pagar
            if ($skipIfSuspendedAndUnpaid && $allServicesSuspended && $lastInvoiceUnpaid) {
                // Si NO está activo el arrendamiento de router, NO generar factura
                if (!$enableRouterRental || $routerRentalAmount <= 0) {
                    Log::info("Cliente {$customer->id}: No se genera factura - todos los servicios suspendidos y última factura sin pagar", [
                        'customer_id' => $customer->id,
                        'all_services_suspended' => $allServicesSuspended,
                        'last_invoice_unpaid' => $lastInvoiceUnpaid,
                    ]);
                    return null;
                }

                // Si SÍ está activo el arrendamiento, generar factura SOLO con el arrendamiento
                Log::info("Cliente {$customer->id}: Generando factura solo con arrendamiento de router", [
                    'customer_id' => $customer->id,
                    'router_rental_amount' => $routerRentalAmount,
                ]);

                return $this->generateRouterRentalOnlyInvoice($customer, $period, $routerRentalAmount, $routerRentalName);
            }

            // Flujo normal: crear el draft invoice
            $periodKey = $period->format('Y-m');
            $invoice = $customer->openDraftInvoice($periodKey);

            DB::transaction(function () use (
                $invoice,
                $services,
                $skipIfSuspendedAndUnpaid,
                $enableRouterRental,
                $routerRentalAmount,
                $routerRentalName,
                $isCustomerUpToDate,
                $customer
            ) {
                $hasServiceItems = false;

                foreach ($services as $service) {
                    // Verificar si debemos omitir el item del servicio
                    $shouldSkipService = false;

                    if ($skipIfSuspendedAndUnpaid) {
                        $isSuspended = $service->service_status === 'suspended';
                        $serviceLastInvoiceUnpaid = $this->serviceLastInvoiceUnpaid($service);

                        if ($isSuspended && $serviceLastInvoiceUnpaid) {
                            $shouldSkipService = true;
                            Log::info("Servicio {$service->id} omitido: está suspendido y última factura sin pagar", [
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

                // Agregar el item de arrendamiento de router si está activo y el cliente está al día
                if ($enableRouterRental && $routerRentalAmount > 0 && $isCustomerUpToDate && $hasServiceItems) {
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
     * Genera una factura solo con el arrendamiento del router
     */
    private function generateRouterRentalOnlyInvoice(
        Customer $customer,
        Carbon $period,
        float $routerRentalAmount,
        string $routerRentalName
    ): Invoice {
        $periodKey = $period->format('Y-m');
        $invoice = $customer->openDraftInvoice($periodKey);

        DB::transaction(function () use ($invoice, $routerRentalAmount, $routerRentalName) {
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

            $invoice->recalcTotals();
            $invoice->update(['state' => 'building']);
        });

        event(new \App\Events\InvoiceItemsCreated($invoice));

        return $invoice;
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
     * Verifica si TODOS los servicios del cliente están suspendidos
     */
    private function allServicesSuspended($services): bool
    {
        if ($services->isEmpty()) {
            return false;
        }

        foreach ($services as $service) {
            if ($service->service_status !== 'suspended') {
                return false;
            }
        }

        return true;
    }

    /**
     * Verifica si la última factura del cliente está sin pagar
     */
    private function customerLastInvoiceUnpaid(Customer $customer): bool
    {
        $lastInvoice = $customer->invoices()
            ->orderBy('created_at', 'desc')
            ->orderBy('id', 'desc')
            ->first();

        if (!$lastInvoice) {
            return false;
        }

        return $lastInvoice->outstanding_balance > 0
            && in_array($lastInvoice->status, ['unpaid', 'overdue']);
    }

    /**
     * Verifica si la última factura del servicio está sin pagar
     */
    private function serviceLastInvoiceUnpaid($service): bool
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
