<?php

namespace App\GraphQL\Queries;

use Illuminate\Support\Facades\Auth;

class NotificationsQuery
{
    /**
     * Devuelve las notificaciones in-app del usuario autenticado,
     * ordenadas de más reciente a más antigua.
     *
     * @param  null  $_
     * @param  array{first?: int}  $args
     * @return array<int, array<string, mixed>>
     */
    public function __invoke($_, array $args): array
    {
        $user = Auth::user();

        if (!$user) {
            return [];
        }

        $first = $args['first'] ?? 20;

        return $user->notifications()
            ->orderByDesc('created_at')
            ->limit($first)
            ->get()
            ->map(fn ($notification) => [
                'id'         => $notification->id,
                'type'       => $notification->type,
                'title'      => data_get($notification->data, 'title', ''),
                'message'    => data_get($notification->data, 'message', ''),
                'level'      => data_get($notification->data, 'level', 'info'),
                'action_url' => data_get($notification->data, 'action_url'),
                'read_at'    => $notification->read_at,
                'created_at' => $notification->created_at,
            ])
            ->all();
    }
}
