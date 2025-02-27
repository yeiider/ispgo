<?php

use Illuminate\Support\Facades\Route;
use Laravel\Nova\Http\Requests\NovaRequest;

/*
|--------------------------------------------------------------------------
| Tool Routes
|--------------------------------------------------------------------------
|
| Here is where you may register Inertia routes for your tool. These are
| loaded by the ServiceProvider of the tool. The routes are protected
| by your tool's "Authorize" middleware by default. Now - go build!
|
*/

Route::get('/', function (NovaRequest $request) {
    return inertia('Mikrotik');
});
Route::get('/planes-ppp', function (NovaRequest $request) {
    return inertia('Mikrotik');
});
Route::get('/ip-pool', function (NovaRequest $request) {
    return inertia('Pool');
});

Route::get('/ipv6-pool', function (NovaRequest $request) {
    return inertia('Ipv6PoolComponent');
});

Route::get('/dhcp-serve', function (NovaRequest $request) {
    return inertia('DHCPPoolComponent');
});
