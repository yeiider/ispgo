<?php

namespace App\GraphQL\Mutations;

use Illuminate\Support\Facades\Auth;

class MarkAllNotificationsRead
{
    /**
     * Marca como leídas todas las notificaciones del usuario autenticado.
     *
     * @param  null  $_
     * @param  array{}  $args
     */
    public function resolve($_, array $args): bool
    {
        $user = Auth::user();

        if (!$user) {
            return false;
        }

        $user->unreadNotifications()->update(['read_at' => now()]);

        return true;
    }
}
