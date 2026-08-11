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
    protected $description = 'Generate invoices every day based on configuration (supports per-customer billing mode and manageable billing cycles)';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle(): void
    {
        $isManageable = GeneralProviderConfig::getManageableBillingCycle();
        $billingDate  = GeneralProviderConfig::getBillingDate();
        $currentDate  = Carbon::now();

        if (!$isManageable && $currentDate->day != $billingDate) {
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
                  ->with(['plan', 'billingCycle']);
            }])
            ->chunk(50, function ($customers) use ($isManageable, $billingDate, $currentDate) {
                foreach ($customers as $customer) {
                    try {
                        if ($customer->usesPerServiceBilling()) {
                            // ── Modo per_service: una factura por servicio ──
                            $this->generatePerServiceInvoices($customer, $isManageable, $billingDate, $currentDate);
                        } else {
                            // ── Modo total (default): una factura por cliente ──
                            $this->generateTotalInvoice($customer, $isManageable, $billingDate, $currentDate);
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
     * con el total de todos sus servicios activos que correspondan al ciclo de hoy.
     */
    protected function generateTotalInvoice(Customer $customer, bool $isManageable, int $defaultBillingDate, Carbon $currentDate): void
    {
        $services = $customer->services;

        if ($services->isEmpty()) {
            return;
        }

        // Si los ciclos de facturación son administrables, filtrar servicios cuyo día de facturación coincida con hoy
        if ($isManageable) {
            $services = $services->filter(function ($service) use ($defaultBillingDate, $currentDate) {
                if ($service->billingCycle && $service->billingCycle->status === 'active') {
                    return (int)$service->billingCycle->billing_day === (int)$currentDate->day;
                }
                return (int)$defaultBillingDate === (int)$currentDate->day;
            });
        }

        if ($services->isEmpty()) {
            return;
        }

        $totalPrice = $services->sum(fn($s) => $s->total_price);

        if ($totalPrice <= 0) {
            return;
        }

        $billingMode = GeneralProviderConfig::getBillingMode();
        $period      = ($billingMode === 'arrears')
            ? $currentDate->copy()->subMonth()->format('Y-m')
            : $currentDate->format('Y-m');
        $defaultUser = GeneralProviderConfig::getDefaultUser();

        // Determinar fecha de vencimiento según el primer ciclo de servicio o por defecto
        $firstCycle = $services->first(fn($s) => $s->billingCycle && $s->billingCycle->status === 'active')?->billingCycle;
        $dueDate    = $firstCycle ? $firstCycle->calculateDueDate($currentDate) : $this->calculateDueDate();

        $invoice = new Invoice();
        $invoice->service_id          = null; // Sin service_id en modo total
        $invoice->customer_id         = $customer->id;
        $invoice->user_id             = $defaultUser;
        $invoice->router_id           = $customer->router_id;
        $invoice->billing_period      = $period;
        $invoice->subtotal            = $totalPrice;
        $invoice->tax                 = 0;
        $invoice->total               = $totalPrice;
        $invoice->amount              = 0;
        $invoice->discount            = 0;
        $invoice->outstanding_balance = $totalPrice;
        $invoice->issue_date          = now();
        $invoice->due_date            = $dueDate;
        $invoice->status              = 'unpaid';
        $invoice->payment_method      = null;
        $invoice->save();

        Log::info("[EVERYDAY] Factura total generada para cliente ID: {$customer->id} - Total: {$totalPrice}");
        $this->info("[EVERYDAY] Factura total generada para cliente ID: {$customer->id} - servicios: {$services->count()} - total: {$totalPrice}");
    }

    /**
     * Modo per_service: genera una factura individual por cada servicio activo
     * del cliente cuya fecha de facturación corresponda al día de hoy.
     */
    protected function generatePerServiceInvoices(Customer $customer, bool $isManageable, int $defaultBillingDate, Carbon $currentDate): void
    {
        $services = $customer->services;

        if ($services->isEmpty()) {
            return;
        }

        $billingMode = GeneralProviderConfig::getBillingMode();
        $period      = ($billingMode === 'arrears')
            ? $currentDate->copy()->subMonth()->format('Y-m')
            : $currentDate->format('Y-m');
        $defaultUser = GeneralProviderConfig::getDefaultUser();

        foreach ($services as $service) {
            try {
                if ($isManageable) {
                    if ($service->billingCycle && $service->billingCycle->status === 'active') {
                        if ((int)$service->billingCycle->billing_day !== (int)$currentDate->day) {
                            continue;
                        }
                    } else {
                        if ((int)$defaultBillingDate !== (int)$currentDate->day) {
                            continue;
                        }
                    }
                }

                $price = $service->total_price;

                if ($price <= 0) {
                    continue;
                }

                $dueDate = ($service->billingCycle && $service->billingCycle->status === 'active')
                    ? $service->billingCycle->calculateDueDate($currentDate)
                    : $this->calculateDueDate();

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
        $dueDay       = GeneralProviderConfig::getPaymentDueDate();
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
