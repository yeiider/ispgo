<?php

namespace App\Models;

use App\Casts\OltgoEncrypted;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Túnel WireGuard entre OLTGO y el MikroTik de una zona.
 */
class NetworkTunnel extends Model
{
    protected $fillable = [
        'router_id',
        'name',
        'type',
        'endpoint_host',
        'endpoint_port',
        'remote_public_key',
        'remote_network',
        'local_address',
        'local_private_key',
        'local_public_key',
        'translated_network',
        'is_active',
    ];

    protected $hidden = ['local_private_key'];

    protected $casts = [
        'local_private_key' => OltgoEncrypted::class,
        'endpoint_port' => 'integer',
        'is_active' => 'boolean',
    ];

    public function router(): BelongsTo
    {
        return $this->belongsTo(Router::class);
    }

    public function olts(): HasMany
    {
        return $this->hasMany(Olt::class);
    }

    /**
     * Genera el par de llaves WireGuard (Curve25519) del lado OLTGO.
     *
     * @return array{private: string, public: string}
     */
    public static function generateKeypair(): array
    {
        $private = random_bytes(32);
        $private[0] = chr(ord($private[0]) & 248);
        $private[31] = chr((ord($private[31]) & 127) | 64);

        return [
            'private' => base64_encode($private),
            'public' => base64_encode(sodium_crypto_scalarmult_base($private)),
        ];
    }

    /**
     * Calcula la llave pública a partir de una privada existente (base64).
     *
     * @return array{private: string, public: string}
     */
    public static function keypairFromPrivate(string $privateB64): array
    {
        $private = base64_decode(trim($privateB64), true);
        if ($private === false || strlen($private) !== 32) {
            throw new \InvalidArgumentException('La llave privada WireGuard no es válida');
        }

        return [
            'private' => base64_encode($private),
            'public' => base64_encode(sodium_crypto_scalarmult_base($private)),
        ];
    }

    /**
     * Traduce una IP de la red real de la zona a la red única que ve OLTGO.
     * Ej.: 136.1.1.101 con 136.1.1.0/24 → 10.200.1.0/24 da 10.200.1.101.
     */
    public function translateHost(string $host): string
    {
        if (!$this->remote_network || !$this->translated_network) {
            return $host;
        }

        [$remoteNet, $remoteBits] = explode('/', $this->remote_network) + [1 => 32];
        [$translatedNet] = explode('/', $this->translated_network);
        $mask = $remoteBits == 0 ? 0 : (~0 << (32 - (int) $remoteBits)) & 0xFFFFFFFF;

        $ip = ip2long($host);
        if ($ip === false || ($ip & $mask) !== (ip2long($remoteNet) & $mask)) {
            return $host;
        }

        return long2ip((ip2long($translatedNet) & $mask) | ($ip & ~$mask & 0xFFFFFFFF));
    }

    /**
     * Comandos para pegar en la terminal del MikroTik de la zona.
     */
    public function mikrotikScript(string $interface = 'wireguard'): string
    {
        $peerIp = explode('/', $this->local_address)[0];
        $lines = [
            sprintf(
                '/interface wireguard peers add interface=%s public-key="%s" allowed-address=%s/32 comment="OLTGO"',
                $interface,
                $this->local_public_key,
                $peerIp
            ),
        ];

        if ($this->remote_network) {
            if ($this->translated_network) {
                $lines[] = sprintf(
                    '/ip firewall nat add chain=dstnat action=netmap src-address=%s dst-address=%s to-addresses=%s comment="OLTGO"',
                    $peerIp,
                    $this->translated_network,
                    $this->remote_network
                );
            }
            $lines[] = sprintf(
                '/ip firewall nat add chain=srcnat action=masquerade src-address=%s dst-address=%s comment="OLTGO"',
                $peerIp,
                $this->remote_network
            );
        }

        return implode("\n", $lines);
    }
}
