<?php

namespace App\GraphQL\Mutations;

use App\Models\FrontendPermission;
use App\Models\Role;
use App\Models\User;

class FrontendPermissionMutation
{
    /**
     * Delete a frontend permission.
     */
    public function delete($root, array $args): array
    {
        $permission = FrontendPermission::find($args['id']);
        if (!$permission) {
            return [
                'success' => false,
                'message' => 'Permiso no encontrado.'
            ];
        }

        $permission->delete();

        return [
            'success' => true,
            'message' => 'Permiso eliminado correctamente.'
        ];
    }

    /**
     * Sync frontend permissions to a role.
     */
    public function syncToRole($root, array $args): Role
    {
        $role = Role::findOrFail($args['role_id']);
        $role->frontendPermissions()->sync($args['permission_ids']);

        return $role->load('frontendPermissions');
    }

    /**
     * Sync frontend permissions directly to a user.
     */
    public function syncToUser($root, array $args): User
    {
        $user = User::findOrFail($args['user_id']);
        $user->frontendPermissions()->sync($args['permission_ids']);

        return $user->load('frontendPermissions');
    }
}
