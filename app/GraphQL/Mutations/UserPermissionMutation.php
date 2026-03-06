<?php

namespace App\GraphQL\Mutations;

use App\Models\User;
use Spatie\Permission\Models\Permission;

class UserPermissionMutation
{
    /**
     * Assign a permission directly to a user
     */
    public function assignPermission($_, array $args)
    {
        $user = User::findOrFail($args['user_id']);
        $permission = Permission::findOrFail($args['permission_id']);

        $user->givePermissionTo($permission);

        return $user->fresh(['roles', 'permissions', 'router']);
    }

    /**
     * Remove a permission from a user
     */
    public function removePermission($_, array $args)
    {
        $user = User::findOrFail($args['user_id']);
        $permission = Permission::findOrFail($args['permission_id']);

        $user->revokePermissionTo($permission);

        return $user->fresh(['roles', 'permissions', 'router']);
    }

    /**
     * Sync permissions to a user (replaces all direct permissions)
     */
    public function syncPermissions($_, array $args)
    {
        $user = User::findOrFail($args['user_id']);
        $permissions = Permission::whereIn('id', $args['permission_ids'])->get();

        $user->syncPermissions($permissions);

        return $user->fresh(['roles', 'permissions', 'router']);
    }
}
