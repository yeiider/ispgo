<?php

namespace Ispgo\Mikrotik\Settings\Config\Sources;

use Ispgo\SettingsManager\Source\ConfigProviderInterface;

/**
 * Opciones de acciones disponibles al suspender un servicio
 */
class SuspendAction implements ConfigProviderInterface
{
    public static function getConfig(): array
    {
        return [
            ['value' => 'disable_queue', 'label' => 'Deshabilitar Queue'],
            ['value' => 'limit_speed', 'label' => 'Limitar Velocidad'],
            ['value' => 'remove_queue', 'label' => 'Eliminar Queue'],
        ];
    }
}
