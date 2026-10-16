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
        'oltgo' => 'Fallas de red: cortes masivos, apagones, OLT sin respuesta o reiniciada, puertos caídos, tarjetas y temperatura',
        'oltgo_capacity' => 'Puertos saturados (más del 80% durante 5 minutos)',
        'oltgo_signal' => 'ONUs perdiendo señal (resumen una vez al día)',
        'oltgo_recovery' => 'Recuperaciones: puerto u OLT de vuelta, temperatura y uso normales',
        'billing' => 'Facturas generadas',
        'suspensions' => 'Cortes de servicio por mora',
        'errors' => 'Errores de procesos automáticos',
    ];

    /** Categorías apagadas mientras no se activen en Configuración, para no llenar el chat. */
    public const OFF_BY_DEFAULT = ['oltgo_capacity', 'oltgo_recovery'];

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

    /** Sin nada guardado, la categoría queda como indique OFF_BY_DEFAULT. */
    public static function categoryEnabled(string $category): bool
    {
        $value = self::get(self::EVENTS_PATH . $category);
        if ($value === null) {
            return !in_array($category, self::OFF_BY_DEFAULT, true);
        }

        return $value !== '0';
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
