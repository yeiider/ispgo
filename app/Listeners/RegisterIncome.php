<?php

namespace App\Listeners;

use App\Events\InvoicePaid;
use App\Nova\Finance\Income;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class RegisterIncome
{
    /**
     * Handle the event.
     */
    public function handle(InvoicePaid $event): void
    {
         Income::create([
             "description" => ,
             "amount" => ,
             "date" => ,
             "category" =>,
             "customer_id" =>
         ]);
    }
}
