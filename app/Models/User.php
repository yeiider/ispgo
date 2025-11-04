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
        'router_id',
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
     * Get the router assigned to this user.
     */
    public function router()
    {
        return $this->belongsTo(Router::class);
    }

    /**
     * Check if user can see all data (super admin, admin without router, or no router assigned).
     */
    public function canSeeAllData(): bool
    {
        // Super-admin siempre ve todo
        if ($this->isSuperAdmin()) {
            return true;
        }

        // Si no tiene router_id, ve todo
        if (!$this->router_id) {
            return true;
        }

        // Si es admin con router_id, solo ve su router
        // Si es usuario normal con router_id, solo ve su router
        return false;
    }

    /**
     * Check if user should filter by router.
     * Returns true if user has router_id and is not super-admin.
     */
    public function shouldFilterByRouter(): bool
    {
        return !$this->isSuperAdmin() && !is_null($this->router_id);
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
