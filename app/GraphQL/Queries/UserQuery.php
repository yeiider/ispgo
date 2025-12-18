<?php

namespace App\GraphQL\Queries;

use App\Models\User;

class UserQuery
{
    /**
     * Get all permissions for a user (including those from roles)
     */
    public function allPermissions($rootValue, array $args)
    {
        $user = $rootValue;

        if (!$user instanceof User) {
            return [];
        }

        return $user->getAllPermissions();
    }
}
