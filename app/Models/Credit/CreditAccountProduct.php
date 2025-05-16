<?php

namespace App\Models\Credit;

use App\Models\Inventory\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CreditAccountProduct extends Model
{
    use HasFactory;

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = true;

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'credit_account_product';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'credit_account_id',
        'product_id',
        'quantity',
        'unit_price',
        'subtotal',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'quantity' => 'integer',
        'unit_price' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the credit account that owns the pivot.
     */
    public function creditAccount(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(CreditAccount::class);
    }

    /**
     * Get the product that owns the pivot.
     */
    public function product(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Calculate the subtotal based on quantity and unit price.
     */
    public function calculateSubtotal()
    {
        $this->subtotal = $this->quantity * $this->unit_price;

        return $this;
    }

    /**
     * Get the formatted unit price.
     */
    public function getFormattedUnitPriceAttribute()
    {
        return '$' . number_format($this->unit_price, 2);
    }

    /**
     * Get the formatted subtotal.
     */
    public function getFormattedSubtotalAttribute()
    {
        return '$' . number_format($this->subtotal, 2);
    }

    /**
     * Get the value of the model's primary key.
     *
     * @return mixed
     */
    public function getKey()
    {
        return $this->getAttribute($this->getKeyName());
    }

    /**
     * Get the primary key for the model.
     *
     * @return string
     */
    public function getKeyName()
    {
        return $this->primaryKey;
    }
}
