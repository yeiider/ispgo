<?php

use App\Http\Controllers\Api\InvoiceController;
use App\Http\Controllers\CustomerAuthController;
use App\Http\Controllers\PaymentController;
use HansSchouten\LaravelPageBuilder\LaravelPageBuilder;
use Illuminate\Support\Facades\Route;

// Rutas de Nova
Route::middleware(['nova'])->prefix('nova')->group(function () {
    \Laravel\Nova\Nova::routes();
});

Route::get('checkout', [\App\Http\Controllers\Checkout::class, 'index'])->name('checkout.index');
Route::get('/payment/configurations', [PaymentController::class, 'getPaymentConfigurations']);


Route::get('/payment/payu/confirmation', [\App\Http\Controllers\Payments\Payu::class, 'confirmation'])->name('payu.confirmation');
Route::get('/payment/payu/response', [\App\Http\Controllers\Payments\Payu::class, 'response'])->name('payu.response');
Route::post('/payment/payu/signature', [\App\Http\Controllers\Payments\Payu::class, 'signature'])->name('payu.signature');

Route::get('/invoice/search', [InvoiceController::class, 'search']);
/*
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
        Route::get('dashboard', function () {
            return view('customer.dashboard');
        });
    });
});*/


Route::middleware(['auth', 'web'])->group(function () {
    Route::prefix('customer')->group(function () {
        Route::get('/account', function () {
            return view('customer.dashboard');
        })->name('account');
    });
});



