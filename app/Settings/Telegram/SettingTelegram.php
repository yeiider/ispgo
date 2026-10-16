<?php

namespace App\Settings\Telegram;

use App\Helpers\ConfigHelper;
use Throwable;

/**
 * Sección "Telegram" en Configuración: avisos de sistema para el equipo técnico.
 */
class SettingTelegram
{
    public const GENERAL_PATH = 'telegram/general/';
    public const EVENTS_PATH = 'telegram/events/';

    /** Categorías de aviso y su etiqueta en Configuración. */
    public const CATEGORIES = [
        'oltgo' => 'Problemas de red de las OLT (cortes, puertos, saturación, temperatura)',
        'billing' => 'Facturas generadas',
        'suspensions' => 'Cortes de servicio por mora',
        'errors' => 'Errores de procesos automáticos',
    ];

    public static function getGeneralSettings(): array
    {
        return [
            'setting' => [
                'label' => 'Bot de Telegram',
                'code' => 'general',
            ],
            'enabled' => [
                'field' => 'boolean-field',
                'label' => 'Enviar avisos de sistema por Telegram',
                'placeholder' => 'Enabled',
            ],
            'bot_token' => [
                'field' => 'password-field',
                'label' => 'Token del bot (lo da @BotFather)',
                'placeholder' => '123456789:AA...',
            ],
            'chat_ids' => [
                'field' => 'text-field',
                'label' => 'IDs de los chats o grupos que reciben los avisos, separados por coma',
                'placeholder' => '-1001234567890',
            ],
        ];
    }

    public static function getEventSettings(): array
    {
        $fields = [
            'setting' => [
                'label' => 'Qué avisar',
                'code' => 'events',
            ],
        ];
        foreach (self::CATEGORIES as $key => $label) {
            $fields[$key] = ['field' => 'boolean-field', 'label' => $label, 'placeholder' => 'Enabled'];
        }

        return $fields;
    }

    public static function enabled(): bool
    {
        return (bool) self::get(self::GENERAL_PATH . 'enabled') && self::token() !== null && self::chatIds() !== [];
    }

    public static function token(): ?string
    {
        return self::get(self::GENERAL_PATH . 'bot_token');
    }

    /** @return string[] */
    public static function chatIds(): array
    {
        return array_values(array_filter(array_map('trim', explode(',', (string) self::get(self::GENERAL_PATH . 'chat_ids')))));
    }

    /** Sin nada guardado, cada categoría está activa. */
    public static function categoryEnabled(string $category): bool
    {
        return self::get(self::EVENTS_PATH . $category) !== '0';
    }

    private static function get(string $path): ?string
    {
        try {
            $value = ConfigHelper::getConfigValue($path);
        } catch (Throwable) {
            return null;
        }

        return $value === '' ? null : $value;
    }
}
