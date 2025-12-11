<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductStock extends Model
{
    use HasFactory;

    protected $table = 'product_warehouse_stock';

    protected $fillable = [
        'product_id',
        'warehouse_id',
        'quantity',
        'min_stock',
        'max_stock',
        'location',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'min_stock' => 'integer',
        'max_stock' => 'integer',
    ];

    /**
     * Obtiene el producto asociado a este stock.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Obtiene la bodega asociada a este stock.
     */
    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    /**
     * Verifica si el stock está por debajo del mínimo.
     */
    public function isBelowMinStock(): bool
    {
        if ($this->min_stock === null) {
            return false;
        }
        return $this->quantity < $this->min_stock;
    }

    /**
     * Verifica si el stock está por encima del máximo.
     */
    public function isAboveMaxStock(): bool
    {
        if ($this->max_stock === null) {
            return false;
        }
        return $this->quantity > $this->max_stock;
    }

    /**
     * Incrementa la cantidad de stock.
     */
    public function incrementStock(int $amount): self
    {
        $this->increment('quantity', $amount);
        return $this;
    }

    /**
     * Decrementa la cantidad de stock.
     */
    public function decrementStock(int $amount): self
    {
        $this->decrement('quantity', $amount);
        return $this;
    }
}
