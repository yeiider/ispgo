<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'sku', 'brand', 'image', 'price',
        'special_price', 'cost_price', 'description',
        'reference', 'taxes', 'status', 'url_key',
        'warehouse_id', 'category_id', 'qty'
    ];

    /**
     * Relación legacy con bodega principal (mantener por compatibilidad).
     */
    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    /**
     * Obtiene la categoría del producto.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Obtiene todas las bodegas donde este producto tiene stock.
     */
    public function warehouses(): BelongsToMany
    {
        return $this->belongsToMany(Warehouse::class, 'product_warehouse_stock')
            ->withPivot('quantity', 'min_stock', 'max_stock', 'location')
            ->withTimestamps();
    }

    /**
     * Obtiene todos los registros de stock del producto en diferentes bodegas.
     */
    public function stocks(): HasMany
    {
        return $this->hasMany(ProductStock::class);
    }

    /**
     * Obtiene el stock total del producto en todas las bodegas.
     */
    public function getTotalStockAttribute(): int
    {
        return $this->stocks()->sum('quantity');
    }

    /**
     * Obtiene el stock del producto en una bodega específica.
     */
    public function getStockInWarehouse(int $warehouseId): ?ProductStock
    {
        return $this->stocks()->where('warehouse_id', $warehouseId)->first();
    }

    /**
     * Obtiene la cantidad de stock en una bodega específica.
     */
    public function getQuantityInWarehouse(int $warehouseId): int
    {
        $stock = $this->getStockInWarehouse($warehouseId);
        return $stock ? $stock->quantity : 0;
    }

    /**
     * Get the credit accounts associated with the product.
     */
    public function creditAccounts(): BelongsToMany
    {
        return $this->belongsToMany(\App\Models\Credit\CreditAccount::class, 'credit_account_product')
            ->using(\App\Models\Credit\CreditAccountProduct::class)
            ->withPivot('quantity', 'unit_price', 'subtotal')
            ->withTimestamps();
    }
}
