<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Ispgo\NapManager\Http\Controllers\NapMapController;

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

// NAP Map routes
Route::get('/map-data', [NapMapController::class, 'getMapData']);
Route::get('/flow-data', [NapMapController::class, 'getFlowData']);
Route::post('/update-node-position/{napBoxId}', [NapMapController::class, 'updateNodePosition']);
Route::post('/update-connection', [NapMapController::class, 'updateConnection']);
