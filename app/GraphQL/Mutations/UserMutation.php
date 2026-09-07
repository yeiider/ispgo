<?php

namespace App\GraphQL\Mutations;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserMutation
{
    /**
     * Create a new user
     */
    public function create($_, array $args)
    {
        $routerIds = $args['router_ids'] ?? (isset($args['router_id']) && !empty($args['router_id']) ? [$args['router_id']] : []);
        unset($args['router_id'], $args['router_ids']);

        $args['password'] = Hash::make($args['password']);

        $user = User::create($args);

        if (!empty($routerIds)) {
            $user->routers()->sync(array_filter($routerIds));
        }

        return $user->fresh(['roles', 'permissions', 'router', 'routers']);
    }

    /**
     * Update an existing user
     */
    public function update($_, array $args)
    {
        $user = User::findOrFail($args['id']);

        $updateData = [];
        if (isset($args['name'])) $updateData['name'] = $args['name'];
        if (isset($args['email'])) $updateData['email'] = $args['email'];
        if (isset($args['telephone'])) $updateData['telephone'] = $args['telephone'];
        if (isset($args['password'])) $updateData['password'] = Hash::make($args['password']);

        $user->update($updateData);

        if (array_key_exists('router_ids', $args)) {
            $user->routers()->sync(array_filter($args['router_ids'] ?? []));
        } elseif (array_key_exists('router_id', $args)) {
            if (!empty($args['router_id'])) {
                $user->routers()->sync([$args['router_id']]);
            } else {
                $user->routers()->detach();
            }
        }

        return $user->fresh(['roles', 'permissions', 'router', 'routers']);
    }

    /**
     * Delete a user
     */
    public function delete($_, array $args)
    {
        $user = User::findOrFail($args['id']);
        $user->delete();

        return [
            'success' => true,
            'message' => 'User deleted successfully'
        ];
    }
}
