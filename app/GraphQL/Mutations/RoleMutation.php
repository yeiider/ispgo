<?php

namespace App\GraphQL\Mutations;

use Spatie\Permission\Models\Role;

class RoleMutation
{
    /**
     * Create a new role
     */
    public function create($_, array $args)
    {
        $role = Role::create([
            'name' => $args['name'],
            'guard_name' => $args['guard_name'] ?? 'web',
        ]);

        return $role->fresh(['permissions', 'users']);
    }

    /**
     * Update an existing role
     */
    public function update($_, array $args)
    {
        $role = Role::findOrFail($args['id']);

        if (isset($args['name'])) {
            $role->name = $args['name'];
            $role->save();
        }

        return $role->fresh(['permissions', 'users']);
    }

    /**
     * Delete a role
     */
    public function delete($_, array $args)
    {
        $role = Role::findOrFail($args['id']);
        $role->delete();

        return [
            'success' => true,
            'message' => 'Role deleted successfully'
        ];
    }
}
