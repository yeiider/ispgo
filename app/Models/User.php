<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Traits\HasRoles;
use Laravel\Passport\HasApiTokens;


class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'telephone',
        'created_by',
        'updated_by',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }



    /**
     * Get all routers assigned to this user (many-to-many relationship).
     */
    public function routers()
    {
        return $this->belongsToMany(Router::class, 'user_router')
            ->withTimestamps();
    }

    /**
     * Get the invoice payments registered by this user.
     */
    public function invoicePayments()
    {
        return $this->hasMany(\App\Models\Invoice\InvoicePayment::class);
    }

    /**
     * Check if user can see all data (no routers assigned).
     * If user has no routers, they see all data.
     * Role permissions control what actions they can perform.
     */
    public function canSeeAllData(): bool
    {
        return $this->routers()->count() === 0;
    }

    /**
     * Check if user should filter by router.
     * Returns true if user has one or more routers assigned.
     */
    public function shouldFilterByRouter(): bool
    {
        return $this->routers()->count() > 0;
    }

    /**
     * Get all router IDs assigned to this user.
     * 
     * @return array
     */
    public function getRouterIds(): array
    {
        return $this->routers()->pluck('routers.id')->toArray();
    }

    public function isSuperAdmin()
    {
        return $this->hasRole('super-admin');
    }

    /**
     * Check if user has admin role (not super-admin).
     */
    public function isAdmin(): bool
    {
        return $this->hasRole('admin') && !$this->isSuperAdmin();
    }

    /**
     * Get all users with the 'technician' role.
     *
     * @return Collection
     */
    public static function technicians()
    {
        return self::role('technician')->get();
    }

    /**
     * Get all users with the 'technician' role.
     *
     * @return Collection
     */
    public static function createInvoiceUsers()
    {
        return self::permission('createInvoice')->get();
    }


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
}
