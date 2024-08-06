<?php

namespace App\Console\Commands;

use App\Models\Services\Service;
use App\Settings\GeneralProviderConfig;
use Illuminate\Console\Command;
use Carbon\Carbon;

class SuspendServicesMonthly extends Command
{
    protected $signature = 'services:suspend_monthly';
    protected $description = 'Suspend services monthly based on cut-off date';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        if (GeneralProviderConfig::getAutomaticCutOff()) {
            $cutOffDate = GeneralProviderConfig::getCutOffDate();
            $currentDate = Carbon::now();

            if ($currentDate->day == $cutOffDate) {
                $services = Service::getAllActiveServicesForInvoiceMonthly();
                foreach ($services as $service) {
                    $service->suspend();
                }
            }
        }
    }
}
