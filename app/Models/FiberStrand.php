<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Hilo de un cable que no está libre: usado (PON, enlace), reservado, vendido a un tercero o dañado.
 * Los hilos que alimentan cajas NAP salen de nap_boxes.fiber_cable_id + fiber_strand.
 */
class FiberStrand extends Model
{
    public const STATUSES = ['used', 'reserved', 'leased', 'damaged'];
    public const USAGES = ['pon', 'link', 'third_party', 'other'];

    protected $fillable = ['fiber_cable_id', 'number', 'status', 'usage', 'olt_id', 'pon_port', 'third_party_id', 'label', 'notes'];

    protected $casts = ['number' => 'integer'];

    public function cable(): BelongsTo
    {
        return $this->belongsTo(FiberCable::class, 'fiber_cable_id');
    }

    public function thirdParty(): BelongsTo
    {
        return $this->belongsTo(FiberThirdParty::class, 'third_party_id');
    }
}
