<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CustomerController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');

Route::post('/customers', [CustomerController::class, 'store'])->middleware('auth:api');

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:api');
