<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoreConfigData extends Model
{
    use HasFactory;

    protected $table = 'core_config_data';

    protected $fillable = [
        'scope_id',
        'path',
        'value',
    ];

    /**
     * The default scope value.
     *
     * @var int
     */
    protected $attributes = [
        'scope' => 0,
    ];
}
