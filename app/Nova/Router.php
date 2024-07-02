<?php

namespace App\Nova;

use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Resource;
use Illuminate\Http\Request;

class Router extends Resource
{
    public static $model = \App\Models\Router::class;

    public static $title = 'name';

    public static $search = [
        'id', 'name', 'ip'
    ];

    public function fields(Request $request)
    {
        return [
            ID::make()->sortable(),
            Text::make(__('Name'), 'name')
                ->sortable()
                ->rules('required', 'max:255'),
            Text::make(__('IP'), 'ip')
                ->sortable()
                ->rules('required', 'max:255'),
            Text::make(__('Failover'), 'failover')
                ->nullable()
                ->sortable()
                ->rules('max:255'),
            Text::make(__('RB User'), 'rb_user')
                ->sortable()
                ->rules('required', 'max:255'),
            Text::make(__('RB Password'), 'rb_password')
                ->sortable()
                ->rules('required', 'max:255'),
            Number::make(__('API Port'), 'api_port')
                ->sortable()
                ->rules('required', 'integer'),
            Number::make(__('WWW Port'), 'www_port')
                ->sortable()
                ->rules('required', 'integer'),
            Text::make(__('LAN Interface'), 'lan_interface')
                ->sortable()
                ->rules('required', 'max:255'),
            Textarea::make(__('IP Ranges'), 'ip_ranges')
                ->nullable()
                ->sortable(),
            Textarea::make(__('Comments'), 'comments')
                ->nullable()
                ->sortable(),
            Text::make(__('Coordinates'), 'coordinates')
                ->nullable()
                ->sortable()
                ->rules('max:255'),
            Text::make(__('Version'), 'version')
                ->nullable()
                ->sortable()
                ->rules('max:255'),
            Text::make(__('Service Cut Type'), 'service_cut_type')
                ->sortable()
                ->rules('required', 'max:255'),
            Boolean::make(__('Add Client in Mikrotik'), 'add_client_mikrotik')
                ->sortable(),
            Boolean::make(__('System Level IPs'), 'system_level_ips')
                ->sortable(),
            Boolean::make(__('Traffic History'), 'traffic_history')
                ->sortable(),
            Boolean::make(__('Simple Queue Control'), 'simple_queue_control')
                ->sortable(),
            Boolean::make(__('PCQ + Addresslist Control'), 'pcq_addresslist_control')
                ->sortable(),
            Boolean::make(__('Hotspot Control'), 'hotspot_control')
                ->sortable(),
            Boolean::make(__('PPPoE Control'), 'pppoe_control')
                ->sortable(),
            Boolean::make(__('IP Bindings'), 'ip_bindings')
                ->sortable(),
            Boolean::make(__('IP/MAC Binding'), 'ip_mac_binding')
                ->sortable(),
            Boolean::make(__('DHCP Leases'), 'dhcp_leases')
                ->sortable(),
            Boolean::make(__('General Failure'), 'general_failure')
                ->sortable(),
            Boolean::make(__('IPv6'), 'ipv6')
                ->sortable(),
        ];
    }
}
