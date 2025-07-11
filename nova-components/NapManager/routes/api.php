<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Ispgo\NapManager\Http\Controllers\NapMapController;
use Ispgo\NapManager\Http\Controllers\NapBoxController;
use Ispgo\NapManager\Http\Controllers\NapPortController;

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

// NAP Box routes
Route::get('/nap-box/{id}', [NapBoxController::class, 'show']);
Route::post('/nap-box', [NapBoxController::class, 'store']);
Route::put('/nap-box/{id}', [NapBoxController::class, 'update']);
Route::delete('/nap-box/{id}', [NapBoxController::class, 'destroy']);

// NAP Port routes
Route::get('/port/{id}', [NapPortController::class, 'show']);
Route::post('/port', [NapPortController::class, 'store']);
Route::put('/port/{id}', [NapPortController::class, 'update']);
Route::delete('/port/{id}', [NapPortController::class, 'destroy']);
