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

// Route for OLT list
Route::get('/olts', function (NovaRequest $request) {
    return inertia('OltLists');
})->name('smartolt.olts');

// Route for OLT details
Route::get('/olts/{oltId}', function (NovaRequest $request, $oltId) {
    return inertia('OltDetail', [
        'oltId' => $oltId,
    ]);
})->name('smartolt.olt.detail');

// Route for ONU details
Route::get('/{resourceId}', function (NovaRequest $request, $resourceId) {
    return inertia('Smartolt', [
        'resourceId' => $resourceId,
        'view' => 'onu',
    ]);
})->name('smartolt.onu');
