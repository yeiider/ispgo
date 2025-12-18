<?php

namespace App\GraphQL\Mutations;

use App\Models\User;
use Spatie\Permission\Models\Role;

class UserRoleMutation
{
    /**
     * Assign a role to a user
     */
    public function assignRole($_, array $args)
    {
        $user = User::findOrFail($args['user_id']);
        $role = Role::findOrFail($args['role_id']);

        $user->assignRole($role);

        return $user->fresh(['roles', 'permissions', 'router']);
    }

    /**
     * Remove a role from a user
     */
    public function removeRole($_, array $args)
    {
        $user = User::findOrFail($args['user_id']);
        $role = Role::findOrFail($args['role_id']);

        $user->removeRole($role);

        return $user->fresh(['roles', 'permissions', 'router']);
    }

    /**
     * Sync roles to a user (replaces all existing roles)
     */
    public function syncRoles($_, array $args)
    {
        $user = User::findOrFail($args['user_id']);
        $roles = Role::whereIn('id', $args['role_ids'])->get();

        $user->syncRoles($roles);

        return $user->fresh(['roles', 'permissions', 'router']);
    }
}
