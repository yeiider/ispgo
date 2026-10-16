<?php

namespace App\GraphQL\Mutations;

use Illuminate\Support\Facades\Auth;

class DeleteNotifications
{
    /**
     * Borra notificaciones del usuario autenticado: las indicadas por ids,
     * o con only_read todas las ya leídas.
     *
     * @param  null  $_
     * @param  array{ids?: array<int, string>, only_read?: bool}  $args
     */
    public function resolve($_, array $args): int
    {
        $user = Auth::user();

        if (!$user) {
            return 0;
        }

        $query = $user->notifications();
        if (!empty($args['ids'])) {
            $query->whereIn('id', $args['ids']);
        } elseif (!empty($args['only_read'])) {
            $query->whereNotNull('read_at');
        } else {
            return 0;
        }

        return $query->delete();
    }
}
