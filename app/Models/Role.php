<?php

namespace App\Models;

use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    /**
     * Get the frontend permissions assigned to this role.
     */
    public function frontendPermissions()
    {
        return $this->belongsToMany(FrontendPermission::class, 'role_frontend_permission');
    }
}
