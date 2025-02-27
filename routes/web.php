<?php

use App\Http\Controllers\Api\InvoiceController;
use App\Http\Controllers\CustomerAccount\CustomerController;
use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerAccount\AuthController;
use App\Http\Controllers\CustomerAccount\DashboardController;
use App\Http\Controllers\CustomerAccount\TicketsController;
use App\Http\Controllers\Api\BoxApi;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Controllers\CustomerAccount\InvoiceController as CustomerInvoiceController;
use App\Http\Controllers\CustomerExportController;



Route::get('checkout', [\App\Http\Controllers\Checkout::class, 'index'])->name('checkout.index');
Route::middleware([\App\Http\Middleware\CheckUserBox::class])->prefix('admin')->group(function () {
    Route::get('pos', [\App\Http\Controllers\Pos::class, 'index'])->name('admin.pos');
    Route::post('/daily-boxes/create', [BoxApi::class, 'createDailyBox']);
});

Route::get('/payment/configurations', [PaymentController::class, 'getPaymentConfigurations']);


//Payu
Route::get('/payment/payu/confirmation', [\App\Http\Controllers\Payments\Payu::class, 'confirmation'])->name('payu.confirmation');
Route::get('/payment/payu/response', [\App\Http\Controllers\Payments\Payu::class, 'response'])->name('payu.response');
Route::post('/payment/payu/signature', [\App\Http\Controllers\Payments\Payu::class, 'signature'])->name('payu.signature');

//Wompi
Route::post('/payment/wompi/signature', [\App\Http\Controllers\Payments\WompiController::class, 'signature'])->name('wompi.signature');
Route::get('/payment/wompi/confirmation', [\App\Http\Controllers\Payments\WompiController::class, 'confirmation'])->name('wompi.confirmation');
Route::get('/payment/wompi/confirmation-link/{incrementId}', [\App\Http\Controllers\Payments\WompiController::class, 'confirmationLink'])->name('wompi.confirmation.link');
Route::post('/payment/handlewompievent', [\App\Http\Controllers\Payments\WompiController::class, 'handlewompievent'])->name('wompi.handlewompievent');


Route::get('/invoice/search', [InvoiceController::class, 'search']);
Route::post('/invoice/apply-payment', [InvoiceController::class, 'registerPayment']);
Route::get('/invoice/find-by-box', [InvoiceController::class, 'getInvoicesForToday']);
Route::get('/invoice/receipt', [InvoiceController::class, 'getReceipt']);
Route::get('/customer/search', [\App\Http\Controllers\Api\CustomerApi::class, 'search']);

Route::get('/', [\App\Http\Controllers\Welcome::class, 'index'])->name('home');
Route::get('/signed/{contractId}', [\App\Http\Controllers\Signed::class, 'index'])->name('signed');
Route::post('/signed/{contractId}', [\App\Http\Controllers\Signed::class, 'signedContract'])->name('signed.signedContract');

Route::middleware('guest:customer')->prefix('customer')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm']);
    Route::post('/login', [AuthController::class, 'login'])->name('customer.login');
    Route::get('/register', [AuthController::class, 'showRegisterForm']);
    Route::post('/register', [AuthController::class, 'register'])->name('customer.register');
    Route::get('/password/reset', function () {
        $routeResetPassword = route('customer.password.reset');
        return Inertia::render('Customer/Authentication/ResetPassword', compact('routeResetPassword'));
    })->name('customer.password.reset.get');
    Route::post('/password/reset', [AuthController::class, 'sendPasswordResetEmail'])->name('customer.password.reset');
    Route::get('/password/create/{token}', [AuthController::class, 'showCreatePassword'])->name('customer.password.create');
    Route::post('/password/create', [AuthController::class, 'createPassword'])->name('customer.password.create.post');
});

Route::get('/export-customers', [CustomerExportController::class, 'exportCsv'])->name('customers.export');

Route::middleware([\App\Http\Middleware\RedirectIfNotCustomer::class])->prefix('customer-account')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('index');
    Route::get('/orders', [DashboardController::class, 'orders'])->name('orders');
    Route::get('/logout', [AuthController::class, 'logout'])->name('customer.logout');
    Route::get('/tickets', [TicketsController::class, 'index'])->name('tickets');
    Route::get('/tickets/create', [TicketsController::class, 'create'])->name('tickets.create');
    Route::post('/tickets/store', [TicketsController::class, 'store'])->name('tickets.store');
    Route::get('/tickets/edit/{id}', [TicketsController::class, 'edit'])->name('tickets.edit');
    Route::get('/invoices', [CustomerInvoiceController::class, 'index'])->name('invoices');
    Route::get('/address-book', [\App\Http\Controllers\CustomerAccount\AddressBook::class, 'index'])->name('addresses');
    Route::get('/address-book/create', [\App\Http\Controllers\CustomerAccount\AddressBook::class, 'create'])->name('address.create');
    route::post('/address-book/store', [\App\Http\Controllers\CustomerAccount\AddressBook::class, 'store'])->name('address.store');
    Route::get('/address-book/edit/{id}', [\App\Http\Controllers\CustomerAccount\AddressBook::class, 'edit'])->name('address.edit');
    Route::put('/address-book/update/{id}', [\App\Http\Controllers\CustomerAccount\AddressBook::class, 'update'])->name('address.update');
    Route::delete('/address-book/delete/{id}', [\App\Http\Controllers\CustomerAccount\AddressBook::class, 'destroy'])->name('address.destroy');
    Route::get('/customer/edit', [CustomerController::class, 'edit'])->name('customer.edit');
    Route::put('/customer/update/{id}', [CustomerController::class, 'update'])->name('customer.update');
    Route::put('/customer/change-password', [CustomerController::class, 'changePassword'])->name('customer.changePassword');
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


Route::get('/401', function () {
    abort(401, 'Unauthorized');
})->name('error.401');


Route::get('/preview-invoice/{id}', [InvoiceController::class, 'previewInvoice'])->name('preview.invoice');

Route::get('/preview-invoice-email/{id}', [InvoiceController::class, 'previewInvoiceEmail']);
