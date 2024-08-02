<?php

use App\Models\Customers\Customer;
use Illuminate\Support\Facades\Route;

Route::post('/handleWebhook', [\Ispgo\Wiivo\Http\Controllers\Wiivo::class, 'handleWebhook'])->name('wiivo.webhook');

Route::get('/link', function () {
    $customer = Customer::findByIdentityDocument("1149686590");
    $invoice = $customer->getLastInvoice();
    $link = \App\PaymentMethods\Wompi::generatedLinkPayment($invoice);
    return response()->json($link);
});
