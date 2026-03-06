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
        $args['password'] = Hash::make($args['password']);

        $user = User::create($args);

        return $user->fresh(['roles', 'permissions', 'router']);
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
        if (isset($args['router_id'])) $updateData['router_id'] = $args['router_id'];
        if (isset($args['password'])) $updateData['password'] = Hash::make($args['password']);

        $user->update($updateData);

        return $user->fresh(['roles', 'permissions', 'router']);
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
