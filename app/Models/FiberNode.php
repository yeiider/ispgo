<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Ispgo\NapManager\Models\NapBox;

/**
 * Punto de la planta externa: central (donde está la OLT), mufla, reserva, poste u ODF.
 * No pertenece a ninguna zona: un mismo troncal puede llevar hilos de varias.
 */
class FiberNode extends Model
{
    public const TYPES = ['central', 'mufla', 'reserva', 'poste', 'odf'];

    protected $fillable = ['name', 'code', 'type', 'latitude', 'longitude', 'address', 'olt_id', 'notes'];

    protected $casts = [
        'latitude' => 'float',
        'longitude' => 'float',
    ];

    public function olt(): BelongsTo
    {
        return $this->belongsTo(Olt::class);
    }

    public function cablesOut(): HasMany
    {
        return $this->hasMany(FiberCable::class, 'from_node_id');
    }

    public function cablesIn(): HasMany
    {
        return $this->hasMany(FiberCable::class, 'to_node_id');
    }

    /**
     * OLT de la central de la que viene la fibra, subiendo por los cables que llegan al nodo.
     */
    public function upstreamOltId(): ?int
    {
        $node = $this;
        $seen = [];
        while ($node && !in_array($node->id, $seen)) {
            if ($node->olt_id) {
                return (int) $node->olt_id;
            }
            $seen[] = $node->id;
            $node = $node->cablesIn()->first()?->fromNode;
        }

        return null;
    }

    public function napBoxes(): HasMany
    {
        return $this->hasMany(NapBox::class, 'fiber_node_id');
    }
}
