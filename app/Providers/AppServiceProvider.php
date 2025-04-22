<?php

namespace App\Providers;

use App\Events\InvoiceCreated;
use App\Events\InvoicePaid;
use App\Listeners\AfterPayingInvoice;
use App\Listeners\SendInvoiceNotification;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\URL;
use Laravel\Passport\Passport;

class AppServiceProvider extends ServiceProvider

{


    /**
     * Register any application services.
     */
    public function register(): void
    {

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Event::listen(
            InvoiceCreated::class,
            [SendInvoiceNotification::class, 'handle']
        );
        Passport::enablePasswordGrant();
        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }
    }
}
