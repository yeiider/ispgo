<?php

namespace App\Console\Commands;

use App\Models\Invoice\Invoice;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CalculateDailyInvoiceBalances extends Command
{
    protected $signature = 'calculate:daily_invoice_balances';
    protected $description = 'Calculate daily invoice balances and store them in the database';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $date = Carbon::now()->format('Y-m-d');
        Invoice::calculateDailyBalances($date);
        $this->info('Daily invoice balances calculated and stored successfully.');
    }
}
