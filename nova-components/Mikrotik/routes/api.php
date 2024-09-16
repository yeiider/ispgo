<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
