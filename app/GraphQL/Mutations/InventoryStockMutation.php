<?php

namespace App\GraphQL\Mutations;

use App\Models\Inventory\Product;
use App\Models\Inventory\ProductStock;
use App\Models\Inventory\Warehouse;
use App\Models\Inventory\Category;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class InventoryStockMutation
{
    /**
     * Crea un nuevo producto.
     */
    public function createProduct($root, array $args)
    {
        $input = $args['input'];
        $product = new Product();
        $product->fill($this->onlyProductFillable($input));
        $product->save();

        return $product->fresh(['category', 'warehouse', 'stocks.warehouse']);
    }

    /**
     * Actualiza un producto existente.
     */
    public function updateProduct($root, array $args)
    {
        $product = Product::findOrFail($args['id']);
        $product->fill($this->onlyProductFillable($args['input']));
        $product->save();

        return $product->fresh(['category', 'warehouse', 'stocks.warehouse']);
    }

    /**
     * Elimina un producto.
     */
    public function deleteProduct($root, array $args)
    {
        try {
            $product = Product::findOrFail($args['id']);

            // Verificar si tiene stock en alguna bodega
            $totalStock = $product->stocks()->sum('quantity');
            if ($totalStock > 0) {
                return [
                    'success' => false,
                    'message' => "No se puede eliminar el producto porque tiene {$totalStock} unidades en stock. Vacíe el stock primero."
                ];
            }

            // Eliminar registros de stock
            $product->stocks()->delete();
            
            // Eliminar el producto
            $product->delete();

            return [
                'success' => true,
                'message' => 'Producto eliminado exitosamente.'
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Error al eliminar el producto: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Crea una nueva bodega.
     */
    public function createWarehouse($root, array $args)
    {
        $input = $args['input'];
        $warehouse = new Warehouse();
        $warehouse->fill($this->onlyWarehouseFillable($input));
        $warehouse->save();

        return $warehouse->fresh(['stocks.product']);
    }

    /**
     * Actualiza una bodega existente.
     */
    public function updateWarehouse($root, array $args)
    {
        $warehouse = Warehouse::findOrFail($args['id']);
        $warehouse->fill($this->onlyWarehouseFillable($args['input']));
        $warehouse->save();

        return $warehouse->fresh(['stocks.product']);
    }

    /**
     * Elimina una bodega.
     */
    public function deleteWarehouse($root, array $args)
    {
        try {
            $warehouse = Warehouse::findOrFail($args['id']);

            // Verificar si tiene stock
            $totalStock = $warehouse->stocks()->sum('quantity');
            if ($totalStock > 0) {
                return [
                    'success' => false,
                    'message' => "No se puede eliminar la bodega porque tiene {$totalStock} unidades en stock. Vacíe el stock primero."
                ];
            }

            // Verificar si tiene productos asignados como bodega principal
            $productsCount = $warehouse->products()->count();
            if ($productsCount > 0) {
                return [
                    'success' => false,
                    'message' => "No se puede eliminar la bodega porque tiene {$productsCount} producto(s) asignado(s) como bodega principal."
                ];
            }

            // Eliminar registros de stock
            $warehouse->stocks()->delete();
            
            // Eliminar la bodega
            $warehouse->delete();

            return [
                'success' => true,
                'message' => 'Bodega eliminada exitosamente.'
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Error al eliminar la bodega: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Crea o actualiza el stock de un producto en una bodega.
     */
    public function upsertProductStock($root, array $args)
    {
        $input = $args['input'];

        return DB::transaction(function () use ($input) {
            // Verificar que el producto existe
            $product = Product::findOrFail($input['product_id']);
            
            // Verificar que la bodega existe
            $warehouse = Warehouse::findOrFail($input['warehouse_id']);

            $stock = ProductStock::updateOrCreate(
                [
                    'product_id' => $input['product_id'],
                    'warehouse_id' => $input['warehouse_id'],
                ],
                [
                    'quantity' => $input['quantity'] ?? 0,
                    'min_stock' => $input['min_stock'] ?? null,
                    'max_stock' => $input['max_stock'] ?? null,
                    'location' => $input['location'] ?? null,
                ]
            );

            return $stock->fresh(['product', 'warehouse']);
        });
    }

    /**
     * Actualiza solo la cantidad de stock.
     */
    public function updateStockQuantity($root, array $args)
    {
        return DB::transaction(function () use ($args) {
            $stock = ProductStock::lockForUpdate()->findOrFail($args['id']);
            $stock->quantity = $args['quantity'];
            $stock->save();

            return $stock->fresh(['product', 'warehouse']);
        });
    }

    /**
     * Incrementa el stock de un producto en una bodega.
     */
    public function incrementStock($root, array $args)
    {
        return DB::transaction(function () use ($args) {
            $stock = ProductStock::lockForUpdate()
                ->where('product_id', $args['product_id'])
                ->where('warehouse_id', $args['warehouse_id'])
                ->first();

            if (!$stock) {
                // Crear nuevo registro si no existe
                $stock = ProductStock::create([
                    'product_id' => $args['product_id'],
                    'warehouse_id' => $args['warehouse_id'],
                    'quantity' => $args['amount'],
                ]);
            } else {
                $stock->incrementStock($args['amount']);
            }

            return $stock->fresh(['product', 'warehouse']);
        });
    }

    /**
     * Decrementa el stock de un producto en una bodega.
     */
    public function decrementStock($root, array $args)
    {
        return DB::transaction(function () use ($args) {
            $stock = ProductStock::lockForUpdate()
                ->where('product_id', $args['product_id'])
                ->where('warehouse_id', $args['warehouse_id'])
                ->first();

            if (!$stock) {
                throw ValidationException::withMessages([
                    'product_id' => ['No existe stock para este producto en la bodega especificada.']
                ]);
            }

            if ($stock->quantity < $args['amount']) {
                throw ValidationException::withMessages([
                    'amount' => ["Stock insuficiente. Disponible: {$stock->quantity}, solicitado: {$args['amount']}."]
                ]);
            }

            $stock->decrementStock($args['amount']);

            return $stock->fresh(['product', 'warehouse']);
        });
    }

    /**
     * Transfiere stock entre bodegas.
     */
    public function transferStock($root, array $args)
    {
        return DB::transaction(function () use ($args) {
            $productId = $args['product_id'];
            $fromWarehouseId = $args['from_warehouse_id'];
            $toWarehouseId = $args['to_warehouse_id'];
            $amount = $args['amount'];

            // Verificar stock origen
            $fromStock = ProductStock::lockForUpdate()
                ->where('product_id', $productId)
                ->where('warehouse_id', $fromWarehouseId)
                ->first();

            if (!$fromStock) {
                throw ValidationException::withMessages([
                    'from_warehouse_id' => ['No existe stock para este producto en la bodega origen.']
                ]);
            }

            if ($fromStock->quantity < $amount) {
                throw ValidationException::withMessages([
                    'amount' => ["Stock insuficiente en bodega origen. Disponible: {$fromStock->quantity}, solicitado: {$amount}."]
                ]);
            }

            // Decrementar origen
            $fromStock->decrementStock($amount);

            // Incrementar destino (crear si no existe)
            $toStock = ProductStock::lockForUpdate()
                ->where('product_id', $productId)
                ->where('warehouse_id', $toWarehouseId)
                ->first();

            if (!$toStock) {
                $toStock = ProductStock::create([
                    'product_id' => $productId,
                    'warehouse_id' => $toWarehouseId,
                    'quantity' => $amount,
                ]);
            } else {
                $toStock->incrementStock($amount);
            }

            return [
                'success' => true,
                'message' => "Se transfirieron {$amount} unidades exitosamente.",
                'from_stock' => $fromStock->fresh(['product', 'warehouse']),
                'to_stock' => $toStock->fresh(['product', 'warehouse']),
            ];
        });
    }

    /**
     * Elimina un registro de stock.
     */
    public function deleteProductStock($root, array $args)
    {
        try {
            $stock = ProductStock::findOrFail($args['id']);

            if ($stock->quantity > 0) {
                return [
                    'success' => false,
                    'message' => "No se puede eliminar el registro porque tiene {$stock->quantity} unidades en stock."
                ];
            }

            $stock->delete();

            return [
                'success' => true,
                'message' => 'Registro de stock eliminado exitosamente.'
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Error al eliminar el registro de stock: ' . $e->getMessage()
            ];
        }
    }

    private function onlyProductFillable(array $input): array
    {
        return array_intersect_key($input, array_flip([
            'name', 'sku', 'brand', 'image', 'price',
            'special_price', 'cost_price', 'description',
            'reference', 'taxes', 'status', 'url_key',
            'warehouse_id', 'category_id', 'qty'
        ]));
    }

    private function onlyWarehouseFillable(array $input): array
    {
        return array_intersect_key($input, array_flip([
            'name', 'address', 'code'
        ]));
    }
}
