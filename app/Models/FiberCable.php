<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Ispgo\NapManager\Models\NapBox;

/**
 * Cable de fibra entre dos nodos (troncal o distribución) con N hilos.
 */
class FiberCable extends Model
{
    public const KINDS = ['transporte', 'troncal', 'distribucion'];

    protected $fillable = ['name', 'code', 'kind', 'fiber_count', 'from_node_id', 'to_node_id', 'route', 'length_m', 'status', 'notes'];

    protected $casts = [
        'route' => 'array',
        'fiber_count' => 'integer',
        'length_m' => 'integer',
    ];

    public function fromNode(): BelongsTo
    {
        return $this->belongsTo(FiberNode::class, 'from_node_id');
    }

    public function toNode(): BelongsTo
    {
        return $this->belongsTo(FiberNode::class, 'to_node_id');
    }

    public function napBoxes(): HasMany
    {
        return $this->hasMany(NapBox::class, 'fiber_cable_id');
    }
}
