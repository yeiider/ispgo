<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role;

class FrontendPermission extends Model
{
    protected $fillable = ['name', 'guard_name'];

    /**
     * Get the roles that have this frontend permission.
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_frontend_permission');
    }

    /**
     * Get the users that have this frontend permission directly.
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_frontend_permission');
    }
}
