<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SmartOltBatch extends Model
{
    protected $table = 'smartolt_batches';
    protected $fillable = ['action', 'sn_list'];
    protected $casts = [
        'sn_list' => 'array',
    ];
}
