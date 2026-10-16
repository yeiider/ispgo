<?php

namespace App\Models;

use App\Casts\OltgoEncrypted;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * OLT que OLTGO consulta por SNMP (solo lectura).
 */
class Olt extends Model
{
    protected $fillable = [
        'router_id',
        'network_tunnel_id',
        'name',
        'vendor',
        'model',
        'host',
        'latitude',
        'longitude',
        'address',
        'snmp_port',
        'snmp_version',
        'snmp_community',
        'snmp_v3_user',
        'snmp_v3_level',
        'snmp_v3_auth_proto',
        'snmp_v3_auth_pass',
        'snmp_v3_priv_proto',
        'snmp_v3_priv_pass',
        'is_active',
    ];

    protected $hidden = ['snmp_community', 'snmp_v3_auth_pass', 'snmp_v3_priv_pass'];

    protected $casts = [
        'snmp_community' => OltgoEncrypted::class,
        'snmp_v3_auth_pass' => OltgoEncrypted::class,
        'snmp_v3_priv_pass' => OltgoEncrypted::class,
        'snmp_port' => 'integer',
        'is_active' => 'boolean',
        'latitude' => 'float',
        'longitude' => 'float',
    ];

    public function router(): BelongsTo
    {
        return $this->belongsTo(Router::class);
    }

    public function networkTunnel(): BelongsTo
    {
        return $this->belongsTo(NetworkTunnel::class);
    }

    public function hasCredentials(): bool
    {
        return $this->snmp_version === '3'
            ? filled($this->snmp_v3_user)
            : filled($this->getRawOriginal('snmp_community'));
    }

    /**
     * IP con la que OLTGO llega a la OLT (traducida si va por un túnel).
     */
    public function getEffectiveHostAttribute(): string
    {
        return $this->networkTunnel ? $this->networkTunnel->translateHost($this->host) : $this->host;
    }
}
