<?php

namespace App\Models;

use App\Models\Services\Service;
use Illuminate\Database\Eloquent\Model;

class ServiceRule extends Model
{
    protected $fillable = [
        'service_id', 'type', 'value', 'cycles', 'cycles_used', 'starts_at'
    ];

    protected $casts = [
        'starts_at' => 'datetime'
    ];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function scopeActive($q)
    {
        return $q->whereColumn('cycles_used', '<', 'cycles');
    }
}
