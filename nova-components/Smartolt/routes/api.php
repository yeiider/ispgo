<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Ispgo\Smartolt\Http\Controllers\OnuController;

/*
|--------------------------------------------------------------------------
| Tool API Routes
|--------------------------------------------------------------------------
|
| Here is where you may register API routes for your tool. These routes
| are loaded by the ServiceProvider of your tool. They are protected
| by your tool's "Authorize" middleware by default. Now, go build!
|
*/

// OLT Management Routes
Route::prefix('olt')->group(function () {
    // GET routes for retrieving information
    Route::get('list', [\Ispgo\Smartolt\Http\Controllers\OltController::class, 'getOlts']);
    Route::get('{oltId}/cards', [\Ispgo\Smartolt\Http\Controllers\OltController::class, 'getOltCardsDetails']);
    Route::get('{oltId}/unconfigured-onus', [\Ispgo\Smartolt\Http\Controllers\OltController::class, 'getUnconfiguredOnusForOlt']);
    Route::get('services', [\Ispgo\Smartolt\Http\Controllers\OltController::class, 'getServices']);
    Route::get('zones', [\Ispgo\Smartolt\Http\Controllers\OltController::class, 'getZones']);
    Route::get('vlans/{oltId}', [\Ispgo\Smartolt\Http\Controllers\OltController::class, 'getVlansByOltId']);
});

// ONU Management Routes
Route::prefix('onu')->group(function () {
    // GET routes for retrieving information
    Route::get('{serviceId}/details', [OnuController::class, 'getDetails']);
    Route::get('{serviceId}/status', [OnuController::class, 'getStatus']);
    Route::get('{serviceId}/config', [OnuController::class, 'getConfig']);
    Route::get('{serviceId}/signal-graph', [OnuController::class, 'getSignalGraph']);
    Route::get('{serviceId}/traffic-graph/{graphType?}', [OnuController::class, 'getTrafficGraph']);

    // POST routes for actions
    Route::post('authorize', [OnuController::class, 'authorize']);
    Route::post('{serviceId}/reboot', [OnuController::class, 'reboot']);
    Route::post('{serviceId}/factory-reset', [OnuController::class, 'factoryReset']);
    Route::post('{serviceId}/enable', [OnuController::class, 'enable']);
    Route::post('{serviceId}/disable', [OnuController::class, 'disable']);
    Route::post('{serviceId}/update-speed-profile', [OnuController::class, 'updateSpeedProfile']);
    Route::post('{serviceId}/update-vlan', [OnuController::class, 'updateVlan']);
    Route::post('{serviceId}/update-wan-mode', [OnuController::class, 'updateWanMode']);
    Route::post('{serviceId}/enable-catv', [OnuController::class, 'enableCatv']);
    Route::post('{serviceId}/disable-catv', [OnuController::class, 'disableCatv']);
});

// Direct ONU API Routes (by external_id)
Route::prefix('api/onu')->group(function () {
    Route::get('traffic-graph/{externalId}/{graphType?}', [OnuController::class, 'getTrafficGraphByExternalId']);
    Route::get('signal-graph/{externalId}', [OnuController::class, 'getSignalGraphByExternalId']);
});
