<?php

namespace Ispgo\Wiivo;

use App\Events\InvoiceIssued;
use App\Events\InvoicePaid;
use Illuminate\Support\ServiceProvider;
use Ispgo\Wiivo\Http\Middleware\Authorize;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Event;
use Ispgo\Wiivo\Listener\GeneratedInvoice;
use Ispgo\Wiivo\Listener\PaymentInvoice;

class WiivoServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // Register any bindings or configurations here
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Event::listen(
            InvoicePaid::class,
            [PaymentInvoice::class, 'handle']
        );
        Event::listen(
            InvoiceIssued::class,
            [GeneratedInvoice::class, 'handle']
        );


        Route::middleware([Authorize::class])
            ->prefix('wiivo/api/')
            ->group(__DIR__ . '/../routes/api.php');
    }

}
