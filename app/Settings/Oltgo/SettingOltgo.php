<?php

namespace App\Settings\Oltgo;

/**
 * Sección "OLTGO" en Configuración: cómo llega ISPGO al servicio Go.
 * La llave OLTGO_SECRET_KEY se queda en el .env (no se guarda en la base de datos).
 */
class SettingOltgo
{
    public const GENERAL_PATH = 'oltgo/general/';
    public const ALERTS_PATH = 'oltgo/alerts/';

    public static function getGeneralSettings(): array
    {
        return [
            'setting' => [
                'label' => 'Conexión con OLTGO',
                'code' => 'general',
            ],
            'enabled' => [
                'field' => 'boolean-field',
                'label' => 'Habilitar OLTGO',
                'placeholder' => 'Enabled',
            ],
            'url' => [
                'field' => 'text-field',
                'label' => 'URL del servicio OLTGO',
                'placeholder' => 'http://oltgo:8090',
            ],
            'timeout' => [
                'field' => 'number-field',
                'label' => 'Tiempo máximo de respuesta (segundos)',
                'placeholder' => '20',
            ],
        ];
    }

    /**
     * Alertas de red: OLTGO detecta los eventos y ISPGO los avisa (comando oltgo:alerts, cada minuto).
     */
    public static function getAlertSettings(): array
    {
        return [
            'setting' => [
                'label' => 'Alertas de red',
                'code' => 'alerts',
            ],
            'enabled' => [
                'field' => 'boolean-field',
                'label' => 'Avisar en ISPGO (campana) a administradores y técnicos',
                'placeholder' => 'Enabled',
            ],
            'whatsapp_enabled' => [
                'field' => 'boolean-field',
                'label' => 'Enviar también por WhatsApp (Wiivo) las alertas de advertencia y críticas',
                'placeholder' => 'Enabled',
            ],
            'whatsapp_numbers' => [
                'field' => 'text-field',
                'label' => 'Celulares para WhatsApp, separados por coma (ej. 3001234567, 3109876543)',
                'placeholder' => '3001234567',
            ],
        ];
    }
}
