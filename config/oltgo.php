<?php

return [
    // URL interna del servicio OLTGO; en Docker/Coolify, por nombre de servicio: http://oltgo:8090
    'url' => env('OLTGO_URL', 'http://127.0.0.1:8080'),
    'timeout' => (int) env('OLTGO_TIMEOUT', 20),

    // Llave compartida con OLTGO: cifra communities y llaves WireGuard y firma
    // las peticiones a OLTGO y las alertas que OLTGO envía por webhook.
    // 32 bytes en base64: php -r "echo base64_encode(random_bytes(32)), PHP_EOL;"
    'secret_key' => env('OLTGO_SECRET_KEY'),
];
