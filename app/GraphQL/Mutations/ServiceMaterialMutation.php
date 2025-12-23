<?php

namespace App\GraphQL\Mutations;

use App\Models\Inventory\EquipmentAssignment;
use App\Models\Inventory\Product;
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
        // Si assignable_to_service es false, lanzamos error.
        // Asumimos que si la columna es null, es false.
        if (!$product->assignable_to_service) {
            throw ValidationException::withMessages([
                'product_id' => ['Este producto no está marcado como asignable a servicios.'],
            ]);
        }

        $quantity = $args['quantity'];
        $fromUserStock = $args['from_user_stock'] ?? false;
        $userId = $args['user_id'] ?? auth()->id();

        // Validar que tengamos un usuario si es desde stock de usuario
        if ($fromUserStock && !$userId) {
            throw ValidationException::withMessages([
                'user_id' => ['Se requiere un usuario para descontar de su stock.'],
            ]);
        }

        return DB::transaction(function () use ($args, $product, $quantity, $fromUserStock, $userId) {
            // Lógica para descontar del stock del usuario
            if ($fromUserStock) {
                $this->decrementUserStock($userId, $product->id, $quantity);
            } else {
                // "Asignación Normal".
                // Aquí podrías implementar descuento de bodega general si fuera necesario.
                // Por ahora, solo registramos la asignación sin descontar de bodega específica
                // o asumimos bodega principal. El requerimiento no especifica bodega para "normal".
            }

            // Crear el registro de asignación al servicio
            return ServiceMaterial::create([
                'service_id' => $args['service_id'],
                'product_id' => $args['product_id'],
                'user_id' => $userId, // Guardamos quién hizo la asignación o de quién se descontó
                'quantity' => $quantity,
                'from_user_stock' => $fromUserStock,
                'notes' => $args['notes'] ?? null,
            ]);
        });
    }

    protected function decrementUserStock($userId, $productId, $neededQty)
    {
        // Buscar asignaciones 'assigned' de este usuario y producto
        // Ordenamos por fecha mas antigua para FIFO? O cualquiera?
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

            if ($assignment->quantity <= $remainingToDeduct) {
                // Consumimos toda esta asignación
                $deducted = $assignment->quantity;
                $assignment->status = 'consumed_in_service'; // O 'returned' o 'installed'?
                // 'consumed_in_service' no es standard en EquipmentAssignmentStatus enum si existe,
                // Pero status es string. Usaremos 'installed' o simplemente bajamos quantity a 0?
                // El modelo EquipmentAssignment parece ser de "prestamo".
                // Si se instala, ya no lo tiene el usuario.
                // Vamos a reducir la cantidad o marcar como 'installed'.
                
                // Opción A: Reducir cantidad. Si 0, delete o status 'consumed'.
                // Vamos a cambiar status a 'installed' si es total, o reducir quantity.
                
                $remainingToDeduct -= $deducted;
                
                // Actualizar assignment
                $assignment->quantity = 0; 
                $assignment->status = 'installed'; // Nuevo estado para indicar que se usó
                $assignment->returned_at = now(); // Técnicamente "devuelto" o "finalizado"
                $assignment->notes = ($assignment->notes ? $assignment->notes . "\n" : "") . "Instalado en servicio (Auto)";
                $assignment->save(); 

            } else {
                // Consumimos parcial
                $assignment->quantity -= $remainingToDeduct;
                // No cambiamos status, sigue teniendo items
                $assignment->save();
                $remainingToDeduct = 0;
            }
        }
    }
}
