<?php

return [
    // Activa o desactiva el registro de peticiones GraphQL.
    'enabled' => env('GRAPHQL_LOG_ENABLED', true),

    // Días que se conservan los registros antes de eliminarse.
    'retention_days' => env('GRAPHQL_LOG_RETENTION_DAYS', 4),

    // Campos raíz que no se registran (introspección y la propia consulta del log).
    'ignored_fields' => [
        '__schema',
        '__type',
        '__typename',
        'graphqlLogs',
        'graphqlLogUsers',
        'graphqlLogFields',
        // Consultas que el frontend repite cada pocos segundos y solo llenarían el log.
        'notifications',
        'unreadNotificationsCount',
        'configValues',
    ],

    // Modelos cuyos cambios no se adjuntan al log.
    'ignored_models' => [
        \App\Models\GraphqlLog::class,
        \App\Models\ActivityLog::class,
        \App\Models\ApiRequestLog::class,
    ],
];
