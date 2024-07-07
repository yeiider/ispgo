<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Invoice;
use Carbon\Carbon;

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
