<?php

namespace App\Providers;

use App\Events\InvoiceCreated;
use App\Events\InvoiceCreatedBefore;
use App\Events\InvoiceItemsCreated;
use App\Events\InvoicePaid;
use App\Listeners\AddServiceLines;
use App\Listeners\AfterPayingInvoice;
use App\Listeners\ApplyRuleInvoice;
use App\Listeners\ApplyBillingNovedades;
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
//        Event::listen(
//            InvoiceCreated::class,
//            [SendInvoiceNotification::class, 'handle']
//        );
        Event::listen(
            InvoiceItemsCreated::class,
            [ApplyRuleInvoice::class, 'handle']
        );
//        Event::listen(
//            InvoiceCreatedBefore::class,
//            [ApplyBillingNovedades::class, 'handle']
//        );
//        Event::listen(
//            InvoiceItemsCreated::class,
//            [AddServiceLines::class, 'handle']
//        );
        Passport::enablePasswordGrant();
        Passport::tokensExpireIn(now()->addDays(15));
        Passport::refreshTokensExpireIn(now()->addDays(30));
        Passport::personalAccessTokensExpireIn(now()->addMonths(6));

        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }
    }
}
