<?php

namespace App\GraphQL\Mutations;

use Spatie\Permission\Models\Permission;

class PermissionMutation
{
    /**
     * Create a new permission
     */
    public function create($_, array $args)
    {
        $permission = Permission::create([
            'name' => $args['name'],
            'guard_name' => $args['guard_name'] ?? 'web',
        ]);

        return $permission->fresh(['roles', 'users']);
    }

    /**
     * Update an existing permission
     */
    public function update($_, array $args)
    {
        $permission = Permission::findOrFail($args['id']);

        if (isset($args['name'])) {
            $permission->name = $args['name'];
            $permission->save();
        }

        return $permission->fresh(['roles', 'users']);
    }

    /**
     * Delete a permission
     */
    public function delete($_, array $args)
    {
        $permission = Permission::findOrFail($args['id']);
        $permission->delete();

        return [
            'success' => true,
            'message' => 'Permission deleted successfully'
        ];
    }
}
