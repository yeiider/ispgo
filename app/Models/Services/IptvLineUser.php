<?php

namespace App\Models\Services;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IptvLineUser extends Model
{
    use HasFactory;

    protected $table = 'iptv_line_users';

    protected $fillable = [
        'service_id',
        'line_id',
        'username',
        'password',
        'max_connections',
        'expire_date',
        'bouquets',
        'status',
    ];

    protected $casts = [
        'expire_date' => 'datetime',
        'bouquets' => 'array',
    ];

    /**
     * Get the service that owns the IPTV line user.
     */
    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}
