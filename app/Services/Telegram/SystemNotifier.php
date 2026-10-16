<?php

namespace App\Services\Telegram;

use App\Jobs\SendTelegramMessage;
use App\Settings\Telegram\SettingTelegram;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * Avisos de sistema para el equipo técnico. Hoy salen por Telegram; cada
 * envío va por la cola para no frenar el proceso que lo genera.
 *
 * Uso: SystemNotifier::send('billing', 'Facturación', '120 facturas generadas', 'success');
 */
class SystemNotifier
{
    private const ICONS = ['danger' => '🔴', 'critical' => '🔴', 'warning' => '🟠', 'success' => '🟢', 'info' => '🔵'];

    /**
     * @param string $category una de SettingTelegram::CATEGORIES
     * @param string $level danger | warning | success | info
     */
    public static function send(string $category, string $title, string $message, string $level = 'info', ?string $url = null): void
    {
        try {
            if (!SettingTelegram::enabled() || !SettingTelegram::categoryEnabled($category)) {
                return;
            }
            $html = self::format($title, $message, $level, $url);
            foreach (SettingTelegram::chatIds() as $chatId) {
                SendTelegramMessage::dispatch($chatId, $html);
            }
        } catch (Throwable $e) {
            // Un aviso nunca debe romper el proceso que lo genera.
            Log::warning('SystemNotifier: ' . $e->getMessage());
        }
    }

    public static function format(string $title, string $message, string $level = 'info', ?string $url = null): string
    {
        $icon = self::ICONS[$level] ?? self::ICONS['info'];
        $html = "{$icon} <b>" . e($title) . "</b>\n" . e($message);
        if ($url) {
            $base = rtrim((string) config('app.frontend_url', ''), '/');
            $link = str_starts_with($url, '/') && $base ? $base . $url : $url;
            if (str_starts_with($link, 'http')) {
                $html .= "\n<a href=\"" . e($link) . '">Ver en ISPGO</a>';
            }
        }

        return $html;
    }
}
