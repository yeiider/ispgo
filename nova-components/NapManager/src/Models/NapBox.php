<?php

namespace Ispgo\NapManager\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class NapBox extends Model
{
    protected $fillable = [
        'name',
        'code',
        'address',
        'latitude',
        'longitude',
        'status',
        'capacity',
        'technology_type',
        'installation_date',
        'brand',
        'model',
        'distribution_order',
        'parent_nap_id', // Para jerarquía de distribución
    ];

    protected $casts = [
        'installation_date' => 'date',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'distribution_order' => 'integer'
    ];
    // Relaciones
    public function ports(): HasMany
    {
        return $this->hasMany(NapPort::class, 'nap_box_id');
    }

    public function childNaps(): HasMany
    {
        return $this->hasMany(self::class, 'parent_nap_id');
    }

    public function parentNap()
    {
        return $this->belongsTo(self::class, 'parent_nap_id');
    }

    public function distributionFlow()
    {
        return $this->hasOne(NapDistribution::class, 'nap_box_id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    // Métodos auxiliares
    public function getAvailablePortsCount()
    {
        return $this->ports()->where('status', 'available')->count();
    }

    public function getOccupancyPercentage()
    {
        $totalPorts = $this->capacity;
        $occupiedPorts = $this->ports()->where('status', 'occupied')->count();

        return $totalPorts > 0 ? round(($occupiedPorts / $totalPorts) * 100, 2) : 0;
    }

    public function getMapMarkerData()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'code' => $this->code,
            'lat' => (float) $this->latitude,
            'lng' => (float) $this->longitude,
            'status' => $this->status,
            'occupancy' => $this->getOccupancyPercentage(),
            'available_ports' => $this->getAvailablePortsCount(),
            'total_capacity' => $this->capacity,
            'address' => $this->address
        ];
    }

}
