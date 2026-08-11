<?php

namespace App\Models\Services;

use Illuminate\Database\Eloquent\Model;

class ServiceMaterial extends Model
{
    protected $fillable = [
        'service_id',
        'product_id',
        'user_id',
        'quantity',
        'from_user_stock',
        'notes',
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'from_user_stock' => 'boolean',
    ];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function product()
    {
        return $this->belongsTo(\App\Models\Inventory\Product::class);
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}
