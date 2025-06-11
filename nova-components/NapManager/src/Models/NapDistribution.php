<?php

namespace Ispgo\NapManager\Models;

use Illuminate\Database\Eloquent\Model;

class NapDistribution extends Model
{
    protected $fillable = [
        'nap_box_id',
        'flow_position_x',
        'flow_position_y',
        'flow_level',
        'connection_data' // JSON para almacenar conexiones del flow
    ];

    protected $casts = [
        'connection_data' => 'array',
        'flow_position_x' => 'decimal:2',
        'flow_position_y' => 'decimal:2'
    ];

    public function napBox()
    {
        return $this->belongsTo(NapBox::class, 'nap_box_id');
    }

    public function getFlowNodeData()
    {
        return [
            'id' => $this->nap_box_id,
            'type' => 'napNode',
            'position' => [
                'x' => (float)$this->flow_position_x,
                'y' => (float)$this->flow_position_y
            ],
            'data' => [
                'label' => $this->napBox->name,
                'code' => $this->napBox->code,
                'status' => $this->napBox->status,
                'occupancy' => $this->napBox->getOccupancyPercentage(),
                'level' => $this->flow_level
            ]
        ];
    }
}
