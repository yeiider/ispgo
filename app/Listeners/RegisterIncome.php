<?php

namespace App\Listeners;

use App\Events\InvoicePaid;
use App\Models\Finance\Income;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class RegisterIncome
{
    /**
     * Handle the event.
     */
    public function handle(InvoicePaid $event): void
    {
        $invoice = $event->invoice;
        Income::create([
             "description" => "invoice",
             "amount" => $invoice->amount,
             "date" => $invoice->issue_date,
             "category" => "invoice",
             "payment_method" => $invoice->payment_method
        ]);
    }
}
