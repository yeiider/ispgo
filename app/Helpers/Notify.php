<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Log;
use Laravel\Nova\Notifications\NovaNotification;

class Notify
{
    public static function notifyError($message): void
    {
        self::sendNotification($message, 'error', 'exclamation-circle');
    }

    public static function notifySuccess($message): void
    {
        self::sendNotification($message, 'success', 'check-circle');
    }

    public static function notifyInfo($message): void
    {
        self::sendNotification($message, 'info', 'info-circle');
    }

    public static function notifyWarning($message): void
    {
        self::sendNotification($message, 'warning', 'exclamation-triangle');
    }

    private static function sendNotification($message, $type, $icon): void
    {
        $admin = \App\Models\User::where('role', 'super-admin')->first();

        if ($admin) {
            $notification = NovaNotification::make()
                ->message($message)
                ->type($type)
                ->icon($icon);

            $admin->notify($notification);
        } else {
            Log::error("Admin user with role 'super-admin' not found.");
        }
    }
}
