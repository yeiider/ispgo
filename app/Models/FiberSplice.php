<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Empalme en una mufla: el hilo in_strand del cable que llega pasa al hilo out_strand del cable que sale.
 */
class FiberSplice extends Model
{
    protected $fillable = ['fiber_node_id', 'in_cable_id', 'in_strand', 'out_cable_id', 'out_strand', 'notes'];

    protected $casts = ['in_strand' => 'integer', 'out_strand' => 'integer'];

    public function node(): BelongsTo
    {
        return $this->belongsTo(FiberNode::class, 'fiber_node_id');
    }
}
