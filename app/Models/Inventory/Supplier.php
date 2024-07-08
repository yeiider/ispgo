<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'contact', 'document', 'description',
        'country', 'city', 'postal_code', 'email', 'phone'
    ];

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
