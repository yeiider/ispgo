<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'sku', 'brand', 'image', 'price',
        'special_price', 'cost_price', 'description',
        'reference', 'taxes', 'status', 'url_key',
        'warehouse_id', 'category_id', 'qty',
        'assignable_to_service'
    ];

    /**
     * Los atributos que deben ser añadidos a la serialización.
     */
    protected $appends = ['image_url'];

    /**
     * Obtiene la URL completa de la imagen del producto.
     * 
     * Siempre genera una URL firmada (presigned) con expiración de 60 minutos
     * para mayor seguridad y compatibilidad con buckets privados.
     */
    protected function imageUrl(): Attribute
    {
        return Attribute::make(
            get: function () {
                if (empty($this->attributes['image'])) {
                    return null;
                }

                $path = $this->attributes['image'];
                $disk = 's3';

                // Si ya es una URL completa, retornarla tal cual
                if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
                    return $path;
                }

                try {
                    $useSignedUrls = config('filesystems.disks.s3.use_signed_urls', true);

                    if ($useSignedUrls) {
                        // URL firmada con expiración de 60 minutos
                        return Storage::disk($disk)->temporaryUrl($path, now()->addMinutes(60));
                    }

                    return Storage::disk($disk)->url($path);
                } catch (\Exception $e) {
                    // Si falla la generación de URL firmada, intentar URL simple
                    \Illuminate\Support\Facades\Log::warning('Product: Error generando URL firmada', [
                        'product_id' => $this->id ?? null,
                        'path' => $path,
                        'error' => $e->getMessage(),
                    ]);
                    
                    return Storage::disk($disk)->url($path);
                }
            }
        );
    }

    /**
     * Obtiene el path original de la imagen (sin URL).
     */
    public function getImagePathAttribute(): ?string
    {
        return $this->attributes['image'] ?? null;
    }

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
