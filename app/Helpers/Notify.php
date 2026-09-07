<?php

namespace App\Helpers;

use App\Models\User;
use App\Notifications\AppNotification;
use Illuminate\Support\Facades\Log;

/**
 * Helper de notificaciones in-app (tabla notifications, canal database).
 *
 * Reemplaza el sistema de notificaciones de Laravel Nova. Mantiene la misma
 * API pública (notifyError/Success/Info/Warning) para no romper los callers
 * existentes. Las notificaciones se entregan a los usuarios con rol
 * super-admin y technician y son consumidas por el frontend vía GraphQL.
 */
class Notify
{
    public static function notifyError(string $message, ?string $title = null, ?string $actionUrl = null, array $metadata = []): void
    {
        self::notifyAdmins($title ?? 'Error', $message, 'danger', $actionUrl, $metadata);
    }

    public static function notifySuccess(string $message, ?string $title = null, ?string $actionUrl = null, array $metadata = []): void
    {
        self::notifyAdmins($title ?? 'Éxito', $message, 'success', $actionUrl, $metadata);
    }

    public static function notifyInfo(string $message, ?string $title = null, ?string $actionUrl = null, array $metadata = []): void
    {
        self::notifyAdmins($title ?? 'Información', $message, 'info', $actionUrl, $metadata);
    }

    public static function notifyWarning(string $message, ?string $title = null, ?string $actionUrl = null, array $metadata = []): void
    {
        self::notifyAdmins($title ?? 'Advertencia', $message, 'warning', $actionUrl, $metadata);
    }

    /**
     * Envía una notificación in-app a todos los usuarios con rol super-admin o technician.
     */
    public static function notifyAdmins(string $title, string $message, string $level = 'info', ?string $actionUrl = null, array $metadata = []): void
    {
        $admins = User::role(['super-admin', 'technician'])->get();

        if ($admins->isEmpty()) {
            Log::error('Notify: no se encontraron usuarios con rol super-admin/technician.');
            return;
        }

        foreach ($admins as $admin) {
            $admin->notify(new AppNotification($title, $message, $level, $actionUrl, $metadata));
        }
    }
}
