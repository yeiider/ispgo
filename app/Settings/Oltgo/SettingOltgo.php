<?php

namespace App\Settings\Oltgo;

/**
 * Sección "OLTGO" en Configuración: cómo llega ISPGO al servicio Go.
 * La llave OLTGO_SECRET_KEY se queda en el .env (no se guarda en la base de datos).
 */
class SettingOltgo
{
    public const GENERAL_PATH = 'oltgo/general/';

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
}
