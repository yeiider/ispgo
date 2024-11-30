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
Route::post('/sync-selected-ppp-profiles', [\Ispgo\Mikrotik\Http\Controller\Api\MikrotikApi::class, 'syncSelectedPPPProfiles'])->name('mikrotik.ppp.profiles.sync.profile');
Route::post('/sync-ppp-profiles', [\Ispgo\Mikrotik\Http\Controller\Api\MikrotikApi::class, 'syncPPPProfiles'])->name('mikrotik.ppp.profiles.sync');
Route::get('/pools', [PoolController::class, 'getPools'])->name('mikrotik.pools');
Route::post('/pools', [PoolController::class, 'createPool'])->name('mikrotik.pools.create');
Route::delete('/pools/{id}', [PoolController::class, 'deletePool'])->name('mikrotik.pools.delete');

Route::get('/ipv6-pools', [\Ispgo\Mikrotik\Http\Controller\Api\IPv6PoolController::class, 'getPools'])->name('mikrotik.pools.ipv6');
Route::post('/ipv6-pools', [\Ispgo\Mikrotik\Http\Controller\Api\IPv6PoolController::class, 'createPool'])->name('mikrotik.pools.ipv6.create');
Route::delete('ipv6-pools/{id}', [\Ispgo\Mikrotik\Http\Controller\Api\IPv6PoolController::class, 'deletePool'])->name('mikrotik.pools.ipv6.delete');

Route::get('dhcp',[\Ispgo\Mikrotik\Http\Controller\Api\DHCPv6Controller::class, 'getDHCPs'])->name('mikrotik.pools.dhcps');
Route::post('dhcp',[\Ispgo\Mikrotik\Http\Controller\Api\DHCPv6Controller::class, 'createDHCP'])->name('mikrotik.pools.dhcps.create');
Route::delete('dhcp/{id}',[\Ispgo\Mikrotik\Http\Controller\Api\DHCPv6Controller::class, 'deleteDHCP'])->name('mikrotik.pools.dhcps.delete');
