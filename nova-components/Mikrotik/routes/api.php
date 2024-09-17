<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Ispgo\Mikrotik\Http\Controller\Api\PoolController;

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

// Route::get('/', function (Request $request) {
//     //
// });

Route::get('/test', [\Ispgo\Mikrotik\Http\Controller\Mikrotik::class, 'index'])->name('mikrotik.index');
Route::get('/add-hotspot', [\Ispgo\Mikrotik\Http\Controller\Mikrotik::class, 'add'])->name('mikrotik.add');
Route::get('/add-queue', [\Ispgo\Mikrotik\Http\Controller\Mikrotik::class, 'addAdvancedSimpleQueue'])->name('mikrotik.simple.add');
Route::get('/add-pppoe', [\Ispgo\Mikrotik\Http\Controller\Mikrotik::class, 'addPPPoEClient'])->name('mikrotik.pppoe.add');
Route::get('/plans', [\Ispgo\Mikrotik\Http\Controller\Api\MikrotikApi::class, 'getPlans'])->name('mikrotik.plans');
Route::get('/ppp-profile', [\Ispgo\Mikrotik\Http\Controller\Api\MikrotikApi::class, 'getPPPProfiles'])->name('mikrotik.ppp.profiles');
Route::post('/sync-selected-ppp-profiles', [\Ispgo\Mikrotik\Http\Controller\Api\MikrotikApi::class, 'syncSelectedPPPProfiles'])->name('mikrotik.ppp.profiles.sync');
Route::post('/sync-ppp-profiles', [\Ispgo\Mikrotik\Http\Controller\Api\MikrotikApi::class, 'syncPPPProfiles'])->name('mikrotik.ppp.profiles.sync');
Route::get('/pools', [PoolController::class, 'getPools'])->name('mikrotik.pools');
Route::post('/pools', [PoolController::class, 'createPool'])->name('mikrotik.pools.create');
Route::delete('/pools/{id}', [PoolController::class, 'deletePool'])->name('mikrotik.pools.delete');
