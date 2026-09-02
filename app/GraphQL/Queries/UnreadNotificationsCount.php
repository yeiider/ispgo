<?php

namespace App\GraphQL\Queries;

use Illuminate\Support\Facades\Auth;

class UnreadNotificationsCount
{
    /**
     * Devuelve la cantidad de notificaciones sin leer del usuario autenticado.
     *
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args): int
    {
        $user = Auth::user();

        if (!$user) {
            return 0;
        }

        return $user->unreadNotifications()->count();
    }
}
