<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'sku', 'brand', 'image', 'price',
        'special_price', 'cost_price', 'description',
        'reference', 'taxes', 'status', 'url_key',
        'warehouse_id', 'category_id'
    ];

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

}
