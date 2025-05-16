<?php

namespace App\Console\Commands;

use App\Models\Services\Service;
use App\Settings\GeneralProviderConfig;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class GenerateInvoicesMonthly extends Command
{
    protected $signature = 'invoice:generate_everyday'; // Cambiar el signature
    protected $description = 'Generate invoices every day based on configuration'; // Cambiar descripción

    public function __construct()
    {
        parent::__construct();
    }

    public function handle(): void
    {
        $billingDate = GeneralProviderConfig::getBillingDate(); // Día configurado para facturación
        $currentDate = Carbon::now();

        if ($currentDate->day == $billingDate) {
            $this->info("[EVERYDAY] Iniciando generación de facturas para servicios...");

            Service::whereNotIn('service_status', ['free', 'pending'])
                ->chunk(50, function ($services) {
                    foreach ($services as $service) {
                        try {
                            $service->generateInvoice();
                            Log::info("[EVERYDAY] Factura generada para servicio ID: {$service->id}");
                            $this->info("[EVERYDAY] Factura generada para servicio ID: {$service->id}");
                        } catch (\Exception $e) {
                            Log::error("[EVERYDAY] Error al generar factura para servicio ID: {$service->id} - {$e->getMessage()}");
                        }
                    }
                });

            $this->info("[EVERYDAY] Generación de facturas completada.");
        } else {
            $this->info("[EVERYDAY] Hoy no es el día configurado para generar facturas ({$billingDate}). No se realizó ninguna acción.");
        }
    }
}
