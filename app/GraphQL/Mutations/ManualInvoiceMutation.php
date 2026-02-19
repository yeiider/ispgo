<?php

namespace App\GraphQL\Mutations;

use App\Models\Customers\Customer;
use App\Models\Invoice\Invoice;
use App\Models\InvoiceAdjustment;
use App\Models\InvoiceItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ManualInvoiceMutation
{
    /**
     * Crear una factura manual.
     *
     * @param null $_
     * @param array $args
     * @return array
     */
    public function create($_, array $args): array
    {
        try {
            DB::beginTransaction();

            // Validar que el cliente existe
            $customer = Customer::findOrFail($args['customer_id']);

            // Validar que no sea una factura de tipo subscription
            if (isset($args['invoice_type']) && $args['invoice_type'] === Invoice::TYPE_SUBSCRIPTION) {
                throw new \Exception('No se pueden crear facturas de tipo subscription manualmente.');
            }

            // Obtener router_id del cliente si no se especifica
            $routerId = $args['router_id'] ?? $customer->router_id;

            // Calcular totales desde los items
            $itemsData = $args['items'];
            $itemsSubtotal = 0;

            foreach ($itemsData as $itemInput) {
                $quantity = $itemInput['quantity'];
                $unitPrice = $itemInput['unit_price'];
                $itemsSubtotal += ($quantity * $unitPrice);
            }

            // Calcular ajustes
            $adjustmentsData = $args['adjustments'] ?? [];
            $charges = 0;
            $discounts = 0;
            $taxes = 0;
            $voids = 0;

            foreach ($adjustmentsData as $adjustment) {
                $amount = $adjustment['amount'];
                $kind = $adjustment['kind'];

                switch ($kind) {
                    case 'charge':
                        $charges += $amount;
                        break;
                    case 'discount':
                        $discounts += abs($amount); // Guardar como positivo
                        break;
                    case 'tax':
                        $taxes += $amount;
                        break;
                    case 'void':
                        $voids += $amount;
                        break;
                }
            }

            // Calcular totales finales
            $subtotal = $itemsSubtotal + $charges - $discounts;
            $total = $subtotal + $taxes + $voids;
            $amountBeforeDiscounts = $itemsSubtotal + $charges + $taxes;

            // Crear la factura
            $invoice = Invoice::create([
                'customer_id' => $args['customer_id'],
                'service_id' => null, // Las facturas manuales no tienen servicio asociado
                'user_id' => Auth::id(),
                'router_id' => $routerId,
                'invoice_type' => Invoice::TYPE_MANUAL,
                'issue_date' => $args['issue_date'],
                'due_date' => $args['due_date'],
                'subtotal' => $subtotal,
                'tax' => $taxes,
                'tax_total' => $taxes,
                'discount' => $discounts,
                'void_total' => $voids,
                'amount_before_discounts' => $amountBeforeDiscounts,
                'total' => $total,
                'amount' => 0, // Sin pagos iniciales
                'outstanding_balance' => $total,
                'status' => $args['status'] ?? 'unpaid',
                'state' => 'issued',
                'notes' => $args['notes'] ?? null,
                'additional_information' => $args['additional_information'] ?? null,
                'customer_name' => "{$customer->first_name} {$customer->last_name}",
            ]);

            // Crear items
            foreach ($itemsData as $itemInput) {
                $quantity = $itemInput['quantity'];
                $unitPrice = $itemInput['unit_price'];
                $subtotalItem = $quantity * $unitPrice;

                InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'service_id' => $itemInput['service_id'] ?? null,
                    'description' => $itemInput['description'],
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'subtotal' => $subtotalItem,
                    'metadata' => $itemInput['metadata'] ?? null,
                ]);
            }

            // Crear ajustes
            foreach ($adjustmentsData as $adjustmentInput) {
                $amount = $adjustmentInput['amount'];
                $kind = $adjustmentInput['kind'];

                // Si es descuento, convertir a negativo
                if ($kind === 'discount' && $amount > 0) {
                    $amount = -abs($amount);
                }

                InvoiceAdjustment::create([
                    'invoice_id' => $invoice->id,
                    'kind' => $kind,
                    'amount' => $amount,
                    'label' => $adjustmentInput['label'],
                    'source_type' => $adjustmentInput['source_type'] ?? null,
                    'source_id' => $adjustmentInput['source_id'] ?? null,
                    'metadata' => $adjustmentInput['metadata'] ?? null,
                    'created_by' => Auth::id(),
                ]);
            }

            DB::commit();

            // Recargar relaciones
            $invoice->load(['items', 'adjustments', 'customer']);

            return [
                'success' => true,
                'message' => 'Factura manual creada exitosamente',
                'invoice' => $invoice,
                'errors' => null,
            ];

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            DB::rollBack();
            Log::error('Error creando factura manual - Cliente no encontrado', [
                'customer_id' => $args['customer_id'] ?? null,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => 'Cliente no encontrado',
                'invoice' => null,
                'errors' => ['El cliente especificado no existe'],
            ];

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creando factura manual', [
                'args' => $args,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return [
                'success' => false,
                'message' => 'Error al crear la factura manual: ' . $e->getMessage(),
                'invoice' => null,
                'errors' => [$e->getMessage()],
            ];
        }
    }

    /**
     * Actualizar una factura manual existente.
     *
     * @param null $_
     * @param array $args
     * @return array
     */
    public function update($_, array $args): array
    {
        try {
            DB::beginTransaction();

            // Buscar la factura
            $invoice = Invoice::findOrFail($args['invoice_id']);

            // Validar que sea una factura manual
            if ($invoice->invoice_type !== Invoice::TYPE_MANUAL) {
                throw new \Exception('Solo se pueden actualizar facturas de tipo manual');
            }

            // Validar que no esté pagada
            if ($invoice->status === Invoice::STATUS_PAID) {
                throw new \Exception('No se puede actualizar una factura que ya ha sido pagada');
            }

            // Actualizar fechas si se proporcionan
            if (isset($args['issue_date'])) {
                $invoice->issue_date = $args['issue_date'];
            }

            if (isset($args['due_date'])) {
                $invoice->due_date = $args['due_date'];
            }

            if (isset($args['notes'])) {
                $invoice->notes = $args['notes'];
            }

            if (isset($args['additional_information'])) {
                $invoice->additional_information = $args['additional_information'];
            }

            if (isset($args['status'])) {
                $invoice->status = $args['status'];
            }

            // Si se envían nuevos items, reemplazar los existentes
            if (isset($args['items'])) {
                // Eliminar items existentes
                $invoice->items()->delete();

                // Crear nuevos items
                $itemsSubtotal = 0;
                foreach ($args['items'] as $itemInput) {
                    $quantity = $itemInput['quantity'];
                    $unitPrice = $itemInput['unit_price'];
                    $subtotalItem = $quantity * $unitPrice;
                    $itemsSubtotal += $subtotalItem;

                    InvoiceItem::create([
                        'invoice_id' => $invoice->id,
                        'service_id' => $itemInput['service_id'] ?? null,
                        'description' => $itemInput['description'],
                        'quantity' => $quantity,
                        'unit_price' => $unitPrice,
                        'subtotal' => $subtotalItem,
                        'metadata' => $itemInput['metadata'] ?? null,
                    ]);
                }
            }

            // Si se envían nuevos ajustes, reemplazar los existentes
            if (isset($args['adjustments'])) {
                // Eliminar ajustes existentes
                $invoice->adjustments()->delete();

                // Crear nuevos ajustes
                foreach ($args['adjustments'] as $adjustmentInput) {
                    $amount = $adjustmentInput['amount'];
                    $kind = $adjustmentInput['kind'];

                    // Si es descuento, convertir a negativo
                    if ($kind === 'discount' && $amount > 0) {
                        $amount = -abs($amount);
                    }

                    InvoiceAdjustment::create([
                        'invoice_id' => $invoice->id,
                        'kind' => $kind,
                        'amount' => $amount,
                        'label' => $adjustmentInput['label'],
                        'source_type' => $adjustmentInput['source_type'] ?? null,
                        'source_id' => $adjustmentInput['source_id'] ?? null,
                        'metadata' => $adjustmentInput['metadata'] ?? null,
                        'created_by' => Auth::id(),
                    ]);
                }
            }

            // Recalcular totales si se modificaron items o ajustes
            if (isset($args['items']) || isset($args['adjustments'])) {
                $invoice->recalcTotals();
            } else {
                $invoice->save();
            }

            DB::commit();

            // Recargar relaciones
            $invoice->refresh();
            $invoice->load(['items', 'adjustments', 'customer']);

            return [
                'success' => true,
                'message' => 'Factura manual actualizada exitosamente',
                'invoice' => $invoice,
                'errors' => null,
            ];

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            DB::rollBack();
            Log::error('Error actualizando factura manual - Factura no encontrada', [
                'invoice_id' => $args['invoice_id'] ?? null,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => 'Factura no encontrada',
                'invoice' => null,
                'errors' => ['La factura especificada no existe'],
            ];

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error actualizando factura manual', [
                'args' => $args,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return [
                'success' => false,
                'message' => 'Error al actualizar la factura manual: ' . $e->getMessage(),
                'invoice' => null,
                'errors' => [$e->getMessage()],
            ];
        }
    }

    /**
     * Eliminar una factura manual.
     *
     * @param null $_
     * @param array $args
     * @return array
     */
    public function delete($_, array $args): array
    {
        try {
            DB::beginTransaction();

            // Buscar la factura
            $invoice = Invoice::findOrFail($args['invoice_id']);

            // Validar que sea una factura manual
            if ($invoice->invoice_type !== Invoice::TYPE_MANUAL) {
                throw new \Exception('Solo se pueden eliminar facturas de tipo manual');
            }

            // Validar que no esté pagada
            if ($invoice->status === Invoice::STATUS_PAID) {
                throw new \Exception('No se puede eliminar una factura que ya ha sido pagada');
            }

            // Eliminar items y ajustes relacionados
            $invoice->items()->delete();
            $invoice->adjustments()->delete();

            // Eliminar la factura
            $invoice->delete();

            DB::commit();

            return [
                'success' => true,
                'message' => 'Factura manual eliminada exitosamente',
                'invoice' => null,
                'errors' => null,
            ];

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            DB::rollBack();
            Log::error('Error eliminando factura manual - Factura no encontrada', [
                'invoice_id' => $args['invoice_id'] ?? null,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => 'Factura no encontrada',
                'invoice' => null,
                'errors' => ['La factura especificada no existe'],
            ];

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error eliminando factura manual', [
                'args' => $args,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return [
                'success' => false,
                'message' => 'Error al eliminar la factura manual: ' . $e->getMessage(),
                'invoice' => null,
                'errors' => [$e->getMessage()],
            ];
        }
    }
}
