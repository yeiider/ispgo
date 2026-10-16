<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use RuntimeException;

/**
 * Cifra con AES-256-GCM usando OLTGO_SECRET_KEY, en un formato que OLTGO (Go)
 * descifra directo: base64(nonce[12] . ciphertext . tag[16]).
 * No usa APP_KEY para que rotar una llave no rompa al otro servicio.
 */
class OltgoEncrypted implements CastsAttributes
{
    public function get($model, string $key, $value, array $attributes): ?string
    {
        if ($value === null || $value === '') {
            return $value;
        }

        $raw = base64_decode($value, true);
        if ($raw === false || strlen($raw) < 28) {
            throw new RuntimeException("Valor cifrado inválido en {$key}");
        }

        $nonce = substr($raw, 0, 12);
        $tag = substr($raw, -16);
        $cipher = substr($raw, 12, -16);
        $plain = openssl_decrypt($cipher, 'aes-256-gcm', self::key(), OPENSSL_RAW_DATA, $nonce, $tag);
        if ($plain === false) {
            throw new RuntimeException("No se pudo descifrar {$key}");
        }

        return $plain;
    }

    public function set($model, string $key, $value, array $attributes): ?string
    {
        if ($value === null || $value === '') {
            return $value;
        }

        $nonce = random_bytes(12);
        $tag = '';
        $cipher = openssl_encrypt($value, 'aes-256-gcm', self::key(), OPENSSL_RAW_DATA, $nonce, $tag, '', 16);

        return base64_encode($nonce . $cipher . $tag);
    }

    private static function key(): string
    {
        $key = base64_decode((string) config('oltgo.secret_key'), true);
        if ($key === false || strlen($key) !== 32) {
            throw new RuntimeException('OLTGO_SECRET_KEY debe ser 32 bytes en base64');
        }

        return $key;
    }
}
