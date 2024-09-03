<?php

use App\Http\Controllers\Api\InvoiceController;
use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Customer\AuthController;
use App\Http\Controllers\Customer\DashboardController;
use App\Http\Controllers\Customer\TicketsController;

// Rutas de Nova
Route::middleware(['nova'])->prefix('nova')->group(function () {
    \Laravel\Nova\Nova::routes();
});

Route::get('checkout', [\App\Http\Controllers\Checkout::class, 'index'])->name('checkout.index');
Route::middleware([])->prefix('admin')->group(function () {
    Route::get('pos', [\App\Http\Controllers\Pos::class, 'index'])->name('admin.pos');
});

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
Route::get('/customer/search', [\App\Http\Controllers\Api\CustomerApi::class, 'search']);


Route::middleware('guest:customer')->prefix('customer')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm']);
    Route::post('/login', [AuthController::class, 'login'])->name('customer.login');
    Route::get('/register', [AuthController::class, 'showRegisterForm']);
    Route::post('/register', [AuthController::class, 'register'])->name('customer.register');

});


Route::middleware('auth:customer')->prefix('customer')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/orders', [DashboardController::class, 'orders'])->name('orders');
    Route::get('/logout', [AuthController::class, 'logout'])->name('customer.logout');
    Route::get('/tickets', [TicketsController::class, 'index'])->name('tickets');
});
