<?php

namespace App\Models\Inventory;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EquipmentAssignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
        'warehouse_id',
        'assigned_at',
        'returned_at',
        'status',
        'quantity',
        'condition_on_assignment',
        'condition_on_return',
        'notes',
    ];

    protected $casts = [
        'quantity' => 'float',
        'assigned_at' => 'datetime',
        'returned_at' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }
}
