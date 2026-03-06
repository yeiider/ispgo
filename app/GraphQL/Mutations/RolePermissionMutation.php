<?php

namespace App\GraphQL\Mutations;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionMutation
{
    /**
     * Assign a permission to a role
     */
    public function assignPermission($_, array $args)
    {
        $role = Role::findOrFail($args['role_id']);
        $permission = Permission::findOrFail($args['permission_id']);

        $role->givePermissionTo($permission);

        return $role->fresh(['permissions', 'users']);
    }

    /**
     * Remove a permission from a role
     */
    public function removePermission($_, array $args)
    {
        $role = Role::findOrFail($args['role_id']);
        $permission = Permission::findOrFail($args['permission_id']);

        $role->revokePermissionTo($permission);

        return $role->fresh(['permissions', 'users']);
    }

    /**
     * Sync permissions to a role (replaces all existing permissions)
     */
    public function syncPermissions($_, array $args)
    {
        $role = Role::findOrFail($args['role_id']);
        $permissions = Permission::whereIn('id', $args['permission_ids'])->get();

        $role->syncPermissions($permissions);

        return $role->fresh(['permissions', 'users']);
    }
}
