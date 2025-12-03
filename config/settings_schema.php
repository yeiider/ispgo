<?php

return [
    // High level sections -> groups -> fields
    'sections' => [
        'general' => [
            'label' => 'General',
            'path' => 'general',
            'groups' => [
                'provider' => [
                    'label' => 'Proveedor',
                    'path' => 'general/provider',
                    'fields' => [
                        'name' => [
                            'label' => 'Nombre del Proveedor',
                            'type' => 'string',
                            'required' => true,
                            'default' => '',
                            'description' => 'Nombre legal o comercial del proveedor.',
                        ],
                        'default_user_id' => [
                            'label' => 'Usuario por defecto',
                            'type' => 'integer',
                            'required' => false,
                            'default' => null,
                            'description' => 'ID del usuario a utilizar por defecto para operaciones automáticas.',
                        ],
                        'timezone' => [
                            'label' => 'Zona horaria',
                            'type' => 'select',
                            'required' => true,
                            'options' => [
                                ['label' => 'UTC', 'value' => 'UTC'],
                                ['label' => 'Bogotá', 'value' => 'America/Bogota'],
                                ['label' => 'Mexico City', 'value' => 'America/Mexico_City'],
                            ],
                            'default' => 'UTC',
                        ],
                    ],
                ],
                'billing' => [
                    'label' => 'Facturación',
                    'path' => 'general/billing',
                    'fields' => [
                        'due_days' => [
                            'label' => 'Días de vencimiento',
                            'type' => 'integer',
                            'default' => 15,
                            'description' => 'Cantidad de días después de la emisión para el vencimiento de la factura.',
                        ],
                        'send_email' => [
                            'label' => 'Enviar email al emitir',
                            'type' => 'boolean',
                            'default' => true,
                        ],
                    ],
                ],
            ],
        ],
    ],
];
