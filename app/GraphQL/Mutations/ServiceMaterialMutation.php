<?php

namespace App\GraphQL\Mutations;

use App\Models\Inventory\EquipmentAssignment;
use App\Models\Inventory\Product;
use App\Models\Inventory\ProductStock;
use App\Models\Inventory\Warehouse;
use App\Models\Services\ServiceMaterial;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;

class ServiceMaterialMutation
{
    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function assign($_, array $args)
    {
        $product = Product::findOrFail($args['product_id']);

        // 1. Validar que el producto sea asignable a servicio
        if (!$product->assignable_to_service) {
            throw ValidationException::withMessages([
                'product_id' => ['Este producto no está marcado como asignable a servicios.'],
            ]);
        }

        $quantity = $args['quantity'];
        $fromUserStock = $args['from_user_stock'] ?? false;
        $userId = $args['user_id'] ?? auth()->id();
        $serviceId = $args['service_id'];
        $type = $args['type'] ?? 'instalacion';
        $notes = $args['notes'] ?? null;

        // Validar que tengamos un usuario si es desde stock de usuario
        if ($fromUserStock && !$userId) {
            throw ValidationException::withMessages([
                'user_id' => ['Se requiere un usuario para descontar de su stock.'],
            ]);
        }

        return DB::transaction(function () use ($args, $product, $quantity, $fromUserStock, $userId, $serviceId, $type, $notes) {
            // Lógica para descontar del stock del usuario o bodega general
            if ($fromUserStock) {
                $this->decrementUserStock($userId, $product->id, $quantity, $serviceId, $notes);
            } else {
                // Asignación desde Bodega General: descontar de ProductStock
                $warehouseId = $args['warehouse_id'] ?? $product->warehouse_id ?? Warehouse::first()?->id;
                if ($warehouseId) {
                    $stock = ProductStock::lockForUpdate()
                        ->where('product_id', $product->id)
                        ->where('warehouse_id', $warehouseId)
                        ->first();

                    if ($stock) {
                        if ($stock->quantity < $quantity) {
                            throw ValidationException::withMessages([
                                'quantity' => ["Stock insuficiente en la bodega seleccionada. Disponible: {$stock->quantity}, Requerido: {$quantity}"],
                            ]);
                        }
                        $stock->decrementStock($quantity);
                    }
                }
            }

            // Crear el registro de asignación al servicio
            return ServiceMaterial::create([
                'service_id' => $serviceId,
                'product_id' => $product->id,
                'user_id' => $userId,
                'quantity' => $quantity,
                'from_user_stock' => $fromUserStock,
                'type' => $type,
                'notes' => $notes,
            ]);
        });
    }

    protected function decrementUserStock($userId, $productId, $neededQty, $serviceId = null, $notes = null)
    {
        // Buscar asignaciones 'assigned' de este usuario y producto
        $assignments = EquipmentAssignment::where('user_id', $userId)
            ->where('product_id', $productId)
            ->where('status', 'assigned')
            ->orderBy('id')
            ->get();

        $totalAvailable = $assignments->sum('quantity');

        if ($totalAvailable < $neededQty) {
            throw ValidationException::withMessages([
                'quantity' => ["El usuario no tiene suficiente stock asignado de este producto. Disponible: {$totalAvailable}, Requerido: {$neededQty}"],
            ]);
        }

        $remainingToDeduct = $neededQty;

        foreach ($assignments as $assignment) {
            if ($remainingToDeduct <= 0) break;

            $noteSuffix = "Instalado en servicio #" . ($serviceId ?? "N/A") . (!empty($notes) ? ": {$notes}" : "");

            if ($assignment->quantity <= $remainingToDeduct) {
                // Consumimos toda esta asignación
                $deducted = $assignment->quantity;
                $remainingToDeduct -= $deducted;

                // Actualizar la asignación a estado 'installed' conservando la cantidad consumida para el historial
                $assignment->status = 'installed';
                $assignment->returned_at = now();
                $assignment->notes = ($assignment->notes ? $assignment->notes . "\n" : "") . $noteSuffix;
                $assignment->save();

            } else {
                // Consumimos parcialmente: decrementar cantidad de la asignación original
                $deducted = $remainingToDeduct;
                $assignment->quantity -= $deducted;
                $assignment->save();

                // Crear nuevo registro de asignación consumida ('installed') para mantener el historial completo del técnico
                $installedAssignment = new EquipmentAssignment();
                $installedAssignment->user_id = $userId;
                $installedAssignment->product_id = $productId;
                $installedAssignment->warehouse_id = $assignment->warehouse_id;
                $installedAssignment->quantity = $deducted;
                $installedAssignment->assigned_at = $assignment->assigned_at ?? now();
                $installedAssignment->returned_at = now();
                $installedAssignment->status = 'installed';
                $installedAssignment->condition_on_assignment = $assignment->condition_on_assignment;
                $installedAssignment->notes = $noteSuffix;
                $installedAssignment->save();

                $remainingToDeduct = 0;
            }
        }
    }
}
