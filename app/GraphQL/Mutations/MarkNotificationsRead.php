<?php

namespace App\GraphQL\Mutations;

use Illuminate\Support\Facades\Auth;

class MarkNotificationsRead
{
    /**
     * Marca como leídas las notificaciones indicadas del usuario autenticado.
     *
     * @param  null  $_
     * @param  array{ids: array<int, string>}  $args
     */
    public function resolve($_, array $args): bool
    {
        $user = Auth::user();

        if (!$user) {
            return false;
        }

        $user->notifications()
            ->whereIn('id', $args['ids'])
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return true;
    }
}
