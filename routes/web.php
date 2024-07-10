<?php

use App\Http\Controllers\CustomerAuthController;
use HansSchouten\LaravelPageBuilder\LaravelPageBuilder;
use Illuminate\Support\Facades\Route;

// Rutas de Nova
Route::middleware(['nova'])->prefix('nova')->group(function () {
    \Laravel\Nova\Nova::routes();
});

// Rutas de autenticación de clientes
Route::prefix('customer')->group(function () {
    Route::get('register', [CustomerAuthController::class, 'showRegistrationForm'])->name('customer.register');
    Route::post('register', [CustomerAuthController::class, 'register']);
    Route::get('login', [CustomerAuthController::class, 'showLoginForm'])->name('customer.login');
    Route::post('login', [CustomerAuthController::class, 'login']);
    Route::post('logout', [CustomerAuthController::class, 'logout'])->name('customer.logout');

    Route::get('password/reset', [CustomerAuthController::class, 'showLinkRequestForm'])->name('customer.password.request');
    Route::post('password/email', [CustomerAuthController::class, 'sendResetLinkEmail'])->name('customer.password.email');
    Route::get('password/reset/{token}', [CustomerAuthController::class, 'showResetForm'])->name('customer.password.reset');
    Route::post('password/reset', [CustomerAuthController::class, 'reset'])->name('customer.password.update');

    Route::middleware('auth.customer')->group(function () {
        Route::get('dashboard', function (){
            return view('customer.dashboard');
        });
    });
});


