<?php

return [
    // URL interna del servicio OLTGO, ej. http://oltgo:8080
    'url' => env('OLTGO_URL', 'http://127.0.0.1:8080'),
    'timeout' => (int) env('OLTGO_TIMEOUT', 20),

    // Llave compartida con OLTGO para cifrar communities y llaves WireGuard.
    // 32 bytes en base64: php -r "echo base64_encode(random_bytes(32)), PHP_EOL;"
    'secret_key' => env('OLTGO_SECRET_KEY'),
];
