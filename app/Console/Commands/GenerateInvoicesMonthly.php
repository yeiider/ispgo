<?php

namespace App\Console\Commands;

use App\Models\Services\Service;
use App\Settings\GeneralProviderConfig;
use Illuminate\Console\Command;

class GenerateInvoicesMonthly extends Command
{
    protected $signature = 'invoice:generated_monthly';
    protected $description = 'generate invoices monthly';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        if (GeneralProviderConfig::getAutomaticInvoiceGeneration()){
            $services = Service::getAllActiveServicesForInvoiceMonthly();
            foreach ($services as $service) {
                $service->generateInvoice();
            }
        }

    }
}
