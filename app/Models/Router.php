<?php

namespace App\Models;

use App\Models\Customers\Customer;
use App\Models\Invoice\Invoice;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Ispgo\NapManager\Models\NapBox;

class Router extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'status',
        'created_by',
        'updated_by',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->created_by = Auth::id();
            $model->updated_by = Auth::id();
        });

        static::updating(function ($model) {
            $model->updated_by = Auth::id();
        });
    }

    public function customers()
    {
        return $this->hasMany(Customer::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    /**
     * Get all users assigned to this router.
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_router')
            ->withTimestamps();
    }

    public function napBoxes(): HasMany
    {
        return $this->hasMany(NapBox::class, 'router_id');
    }
}
