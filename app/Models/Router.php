<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Router extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'ip',
        'failover',
        'rb_user',
        'rb_password',
        'api_port',
        'www_port',
        'lan_interface',
        'ip_ranges',
        'comments',
        'coordinates',
        'version',
        'service_cut_type',
        'add_client_mikrotik',
        'system_level_ips',
        'traffic_history',
        'simple_queue_control',
        'pcq_addresslist_control',
        'hotspot_control',
        'pppoe_control',
        'ip_bindings',
        'ip_mac_binding',
        'dhcp_leases',
        'general_failure',
        'ipv6',
    ];
}
