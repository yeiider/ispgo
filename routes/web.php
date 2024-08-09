<?php

use App\Http\Controllers\Api\InvoiceController;
use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Customer\AuthController;
use App\Http\Controllers\Customer\DashboardController;
use App\Http\Controllers\Customer\TicketsController;
use App\Http\Middleware\AllowLogin;
use App\Http\Middleware\AllowCustomerRegistration;
use Illuminate\Http\Request;
use Inertia\Inertia;

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

//Customer routes
Route::middleware(\App\Http\Middleware\RedirectIfCustomer::class)->prefix('customer')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->middleware(AllowLogin::class);
    Route::post('/login', [AuthController::class, 'login'])->name('customer.login')->middleware(AllowLogin::class);
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->middleware(AllowCustomerRegistration::class);
    Route::post('/register', [AuthController::class, 'register'])->name('customer.register')->middleware(AllowCustomerRegistration::class);
});


Route::middleware([\App\Http\Middleware\RedirectIfNotCustomer::class])->prefix('customer')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/orders', [DashboardController::class, 'orders'])->name('orders');
    Route::get('/logout', [AuthController::class, 'logout'])->name('customer.logout');
    Route::get('/tickets', [TicketsController::class, 'index'])->name('tickets');
});


Route::get('/email/verify', function () {
    return Inertia::render('Customer/VerifyEmail', [
        'status' => session('status')
    ]);
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (\Illuminate\Foundation\Auth\EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect('/home');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('message', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');
