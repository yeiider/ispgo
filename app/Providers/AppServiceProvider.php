<?php

namespace App\Providers;


use App\Events\FinalizeInvoice;
use App\Events\FinalizeInvoiceBySchedule;
use App\Events\InvoiceCreated;
use App\Events\InvoiceIssued;
use App\Events\InvoicePaid;
use App\Listeners\CancelOnePayChargeOnExternalPayment;
use App\Listeners\RegisterIncome;
use App\Listeners\AfterPayingInvoice;
use App\Events\InvoiceItemsCreated;
use App\Listeners\ApplyBillingNovedades;
use App\Listeners\ApplyRuleInvoice;
use App\Listeners\FinalizeBuildInvoiceToSchedule;
use App\Listeners\SendInvoiceNotification;
use App\Listeners\UpdateStateToIssued;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
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
        $this->bootEvents();
        Passport::enablePasswordGrant();
        Passport::tokensExpireIn(now()->addDays(15));
        Passport::refreshTokensExpireIn(now()->addDays(30));
        Passport::personalAccessTokensExpireIn(now()->addMonths(6));

        if (config('app..env') === 'production') {
            URL::forceScheme('https');
        }
    }

    private function bootEvents(): void
    {
        Event::listen(
            FinalizeInvoiceBySchedule::class,
            [FinalizeBuildInvoiceToSchedule::class, 'handle']
        );
        Event::listen(
            FinalizeInvoice::class,
            [UpdateStateToIssued::class, 'handle']
        );
        Event::listen(
            InvoiceIssued::class,
            [SendInvoiceNotification::class, 'handle']
        );

        // When an invoice is paid
        Event::listen(
            InvoicePaid::class,
            [RegisterIncome::class, 'handle']
        );
        Event::listen(
            InvoicePaid::class,
            [AfterPayingInvoice::class, 'handle']
        );
        // Cancel outstanding OnePay charge if paid by another method
        Event::listen(
            InvoicePaid::class,
            [CancelOnePayChargeOnExternalPayment::class, 'handle']
        );
    }
}
