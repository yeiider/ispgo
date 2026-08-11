<?php

namespace Ispgo\Mikrotik\Settings\Config\Sources;

use Ispgo\SettingsManager\Source\ConfigProviderInterface;

/**
 * Opciones de acciones disponibles al activar un servicio
 */
class ActivateAction implements ConfigProviderInterface
{
    public static function getConfig(): array
    {
        return [
            ['value' => 'enable_queue', 'label' => 'Habilitar Queue'],
            ['value' => 'restore_speed', 'label' => 'Restaurar Velocidad'],
            ['value' => 'create_queue', 'label' => 'Crear Queue'],
        ];
    }
}
