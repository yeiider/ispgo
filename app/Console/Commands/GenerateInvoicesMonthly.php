<?php

namespace App\Console\Commands;

use App\Models\Services\Service;
use App\Settings\GeneralProviderConfig;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

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
        if (GeneralProviderConfig::getAutomaticInvoiceGeneration()) {
            Service::where('service_status', '!=', 'free')
                ->chunk(50, function ($services) {
                    foreach ($services as $service) {
                        try {
                            $service->generateInvoice();
                            $this->info("Factura generada para servicio ID: {$service->id}");
                        } catch (\Exception $e) {
                            Log::error("Error al generar factura para el servicio ID: {$service->id} - {$e->getMessage()}");
                        }
                    }
                });
        }
    }

}
