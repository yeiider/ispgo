<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Warehouse extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'address', 'code'];

    /**
     * Relación legacy con productos (mantener por compatibilidad).
     * Productos que tienen esta bodega como principal.
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Obtiene todos los productos con stock en esta bodega.
     */
    public function productsWithStock(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_warehouse_stock')
            ->withPivot('quantity', 'min_stock', 'max_stock', 'location')
            ->withTimestamps();
    }

    /**
     * Obtiene todos los registros de stock en esta bodega.
     */
    public function stocks(): HasMany
    {
        return $this->hasMany(ProductStock::class);
    }

    /**
     * Obtiene el stock de un producto específico en esta bodega.
     */
    public function getProductStock(int $productId): ?ProductStock
    {
        return $this->stocks()->where('product_id', $productId)->first();
    }

    /**
     * Obtiene la cantidad total de productos en stock en esta bodega.
     */
    public function getTotalStockAttribute(): int
    {
        return $this->stocks()->sum('quantity');
    }

    /**
     * Obtiene los productos con stock bajo en esta bodega.
     */
    public function getLowStockProducts()
    {
        return $this->stocks()
            ->whereNotNull('min_stock')
            ->whereRaw('quantity < min_stock')
            ->with('product')
            ->get();
    }
}
