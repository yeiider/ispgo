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

// ONU Management Routes
Route::prefix('onu')->group(function () {
    // GET routes for retrieving information
    Route::get('{serviceId}/details', [OnuController::class, 'getDetails']);
    Route::get('{serviceId}/status', [OnuController::class, 'getStatus']);
    Route::get('{serviceId}/config', [OnuController::class, 'getConfig']);
    Route::get('{serviceId}/signal-graph', [OnuController::class, 'getSignalGraph']);
    Route::get('{serviceId}/traffic-graph/{graphType?}', [OnuController::class, 'getTrafficGraph']);

    // POST routes for actions
    Route::post('{serviceId}/reboot', [OnuController::class, 'reboot']);
    Route::post('{serviceId}/factory-reset', [OnuController::class, 'factoryReset']);
    Route::post('{serviceId}/enable', [OnuController::class, 'enable']);
    Route::post('{serviceId}/disable', [OnuController::class, 'disable']);
    Route::post('{serviceId}/update-speed-profile', [OnuController::class, 'updateSpeedProfile']);
    Route::post('{serviceId}/update-vlan', [OnuController::class, 'updateVlan']);
    Route::post('{serviceId}/update-wan-mode', [OnuController::class, 'updateWanMode']);
});
