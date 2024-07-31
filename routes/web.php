<?php

use App\Http\Controllers\Api\InvoiceController;
use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Customer\AuthController;
use App\Http\Controllers\Customer\DashboardController;

// Rutas de Nova
Route::middleware(['nova'])->prefix('nova')->group(function () {
    \Laravel\Nova\Nova::routes();
});

Route::get('checkout', [\App\Http\Controllers\Checkout::class, 'index'])->name('checkout.index');
Route::get('/payment/configurations', [PaymentController::class, 'getPaymentConfigurations']);


//Payu
Route::get('/payment/payu/confirmation', [\App\Http\Controllers\Payments\Payu::class, 'confirmation'])->name('payu.confirmation');
Route::get('/payment/payu/response', [\App\Http\Controllers\Payments\Payu::class, 'response'])->name('payu.response');
Route::post('/payment/payu/signature', [\App\Http\Controllers\Payments\Payu::class, 'signature'])->name('payu.signature');

//Wompi
Route::post('/payment/wompi/signature', [\App\Http\Controllers\Payments\WompiController::class, 'signature'])->name('wompi.signature');
Route::get('/payment/wompi/confirmation', [\App\Http\Controllers\Payments\WompiController::class, 'confirmation'])->name('wompi.confirmation');
Route::post('/payment/handlewompievent', [\App\Http\Controllers\Payments\WompiController::class, 'handlewompievent'])->name('wompi.handlewompievent');


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

Route::middleware('guest:customer')->prefix('customer')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm']);
    Route::post('/login', [AuthController::class, 'login'])->name('customer.login');
    Route::get('/register', [AuthController::class, 'showRegistrationForm']);
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/orders', [DashboardController::class, 'orders'])->name('orders');
});
