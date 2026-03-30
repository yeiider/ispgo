<?php

namespace App\Console\Commands;

use App\Models\Customers\Customer;
use App\Models\Invoice\Invoice;
use App\Models\Services\Service;
use App\Settings\GeneralProviderConfig;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class GenerateInvoicesMonthly extends Command
{
    protected $signature = 'invoice:generate_everyday';
    protected $description = 'Generate invoices every day based on configuration (supports per-customer billing mode)';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle(): void
    {
        $billingDate = GeneralProviderConfig::getBillingDate();
        $currentDate = Carbon::now();

        if ($currentDate->day != $billingDate) {
            $this->info("[EVERYDAY] Hoy no es el día configurado para generar facturas ({$billingDate}). No se realizó ninguna acción.");
            return;
        }

        $this->info("[EVERYDAY] Iniciando generación de facturas para servicios...");

        // -------------------------------------------------------
        // Recorrer clientes con servicios activos
        // -------------------------------------------------------
        Customer::withoutGlobalScope('router_filter')
            ->whereHas('services', function ($q) {
                $q->withoutGlobalScope('router_filter')
                  ->whereNotIn('service_status', ['free', 'pending', 'inactive']);
            })
            ->with(['services' => function ($q) {
                $q->withoutGlobalScope('router_filter')
                  ->whereNotIn('service_status', ['free', 'pending', 'inactive'])
                  ->with('plan');
            }])
            ->chunk(50, function ($customers) {
                foreach ($customers as $customer) {
                    try {
                        if ($customer->usesPerServiceBilling()) {
                            // ── Modo per_service: una factura por servicio ──
                            $this->generatePerServiceInvoices($customer);
                        } else {
                            // ── Modo total (default): una factura por cliente ──
                            $this->generateTotalInvoice($customer);
                        }
                    } catch (\Exception $e) {
                        Log::error("[EVERYDAY] Error al generar factura para cliente ID: {$customer->id} - {$e->getMessage()}");
                        $this->error("[EVERYDAY] Error al generar factura para cliente ID: {$customer->id}");
                    }
                }
            });

        $this->info("[EVERYDAY] Generación de facturas completada.");
    }

    /**
     * Modo por defecto: genera UNA sola factura por cliente
     * con el total de todos sus servicios activos. No se vincula service_id.
     */
    protected function generateTotalInvoice(Customer $customer): void
    {
        $services = $customer->services;

        if ($services->isEmpty()) {
            return;
        }

        $totalPrice = $services->sum(fn($s) => $s->plan?->monthly_price ?? 0);

        if ($totalPrice <= 0) {
            return;
        }

        $period       = Carbon::now()->format('Y-m');
        $defaultUser  = GeneralProviderConfig::getDefaultUser();
        $dueDate      = $this->calculateDueDate();

        $invoice = new Invoice();
        $invoice->service_id         = null; // Sin service_id en modo total
        $invoice->customer_id        = $customer->id;
        $invoice->user_id            = $defaultUser;
        $invoice->router_id          = $customer->router_id;
        $invoice->billing_period     = $period;
        $invoice->subtotal           = $totalPrice;
        $invoice->tax                = 0;
        $invoice->total              = $totalPrice;
        $invoice->amount             = 0;
        $invoice->discount           = 0;
        $invoice->outstanding_balance = $totalPrice;
        $invoice->issue_date         = now();
        $invoice->due_date           = $dueDate;
        $invoice->status             = 'unpaid';
        $invoice->payment_method     = null;
        $invoice->save();

        Log::info("[EVERYDAY] Factura total generada para cliente ID: {$customer->id} - Total: {$totalPrice}");
        $this->info("[EVERYDAY] Factura total generada para cliente ID: {$customer->id} - servicios: {$services->count()} - total: {$totalPrice}");
    }

    /**
     * Modo per_service: genera una factura individual por cada servicio activo
     * del cliente. Cada factura incluye el service_id correspondiente.
     */
    protected function generatePerServiceInvoices(Customer $customer): void
    {
        $services = $customer->services;

        if ($services->isEmpty()) {
            return;
        }

        $period      = Carbon::now()->format('Y-m');
        $defaultUser = GeneralProviderConfig::getDefaultUser();
        $dueDate     = $this->calculateDueDate();

        foreach ($services as $service) {
            try {
                $price = $service->plan?->monthly_price ?? 0;

                if ($price <= 0) {
                    continue;
                }

                $invoice = new Invoice();
                $invoice->service_id          = $service->id; // Vincula el service_id
                $invoice->customer_id         = $customer->id;
                $invoice->user_id             = $defaultUser;
                $invoice->router_id           = $service->router_id ?? $customer->router_id;
                $invoice->billing_period      = $period;
                $invoice->subtotal            = $price;
                $invoice->tax                 = 0;
                $invoice->total               = $price;
                $invoice->amount              = 0;
                $invoice->discount            = 0;
                $invoice->outstanding_balance = $price;
                $invoice->issue_date          = now();
                $invoice->due_date            = $dueDate;
                $invoice->status              = 'unpaid';
                $invoice->payment_method      = null;
                $invoice->save();

                Log::info("[EVERYDAY] Factura por servicio generada - Cliente ID: {$customer->id}, Servicio ID: {$service->id}, Total: {$price}");
                $this->info("[EVERYDAY] Factura por servicio generada - Cliente ID: {$customer->id}, Servicio ID: {$service->id}");
            } catch (\Exception $e) {
                Log::error("[EVERYDAY] Error al generar factura para servicio ID: {$service->id} (cliente {$customer->id}) - {$e->getMessage()}");
            }
        }
    }

    /**
     * Calcula la fecha de vencimiento según la configuración global.
     */
    protected function calculateDueDate(): Carbon
    {
        $dueDay      = GeneralProviderConfig::getPaymentDueDate();
        $currentMonth = now()->month;
        $currentYear  = now()->year;

        if ($dueDay < now()->day) {
            $dueMonth = ($currentMonth == 12) ? 1 : $currentMonth + 1;
            $dueYear  = ($currentMonth == 12) ? $currentYear + 1 : $currentYear;
        } else {
            $dueMonth = $currentMonth;
            $dueYear  = $currentYear;
        }

        return Carbon::create($dueYear, $dueMonth, $dueDay, 0, 0, 0);
    }
}
