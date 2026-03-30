<?php

namespace App\Console\Commands;

use App\Models\Invoice\Invoice;
use App\Services\Payments\OnePay\OnePayHandler;
use App\Settings\GeneralProviderConfig;
use App\Settings\OnePaySettings;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class OnePayAutoCreateCharges extends Command
{
    protected $signature = 'onepay:auto-create-charges {--router= : ID del router a procesar (opcional)} {--force : Run regardless of configured day} {--dry-run : Only show what would be processed}';

    protected $description = 'Busca facturas no pagadas y crea o reenvía cobros en OnePay según configuración';

    public function handle(): int
    {
        if (!OnePaySettings::enabled()) {
            $this->warn('OnePay no está habilitado. Abortando.');
            return self::SUCCESS; // not an error, just skip
        }

        $baseUrl = OnePaySettings::baseUrl();
        $token = OnePaySettings::apiToken();
        if (!$baseUrl || !$token) {
            $this->error('Configuración de OnePay incompleta (base_url o api_token).');
            return self::FAILURE;
        }

        $todayDay = (int) now()->day;
        $forced = (bool) $this->option('force');
        $dryRun = (bool) $this->option('dry-run');
        $routerId = $this->option('router');

        $processed = 0;
        $created = 0;
        $resent = 0;
        $errors = 0;

        $this->info('Iniciando procesamiento de facturas no pagadas...');

        // Obtener todos los routers
        $allRouters = $routerId
            ? \App\Models\Router::where('id', $routerId)->get()
            : \App\Models\Router::all();

        if ($allRouters->isEmpty()) {
            $this->error('No se encontraron routers para procesar.');
            return self::FAILURE;
        }

        // Filtrar routers que deben ejecutarse hoy
        $routersToProcess = $allRouters->filter(function ($router) use ($todayDay, $forced) {
            $routerAutoCreateDay = OnePaySettings::autoCreateDay($router->id);

            if ($forced) {
                return true; // Si es forced, procesar todos
            }

            if (!$routerAutoCreateDay) {
                $this->line("Router {$router->name} (ID: {$router->id}): No tiene onepay_auto_create_day configurado. Omitiendo.");
                return false;
            }

            if ($todayDay !== (int) $routerAutoCreateDay) {
                $this->line("Router {$router->name} (ID: {$router->id}): Configurado para día {$routerAutoCreateDay}, hoy es {$todayDay}. Omitiendo.");
                return false;
            }

            return true;
        });

        if ($routersToProcess->isEmpty()) {
            $this->info('No hay routers configurados para ejecutarse hoy.');
            return self::SUCCESS;
        }

        if ($forced) {
            $this->warn('Ejecución forzada --force: procesando todos los routers sin validar día.');
        }

        $this->info("Routers a procesar hoy: {$routersToProcess->count()}");

        foreach ($routersToProcess as $router) {
            $this->info("Procesando router {$router->name} (ID: {$router->id})");

            // Obtener el día configurado para facturación de este router
            $billingDate = GeneralProviderConfig::getBillingDate($router->id);

            // Selecciona todas salvo las pagadas/canceladas
            // Filtra por issue_date: solo facturas donde el día del mes >= día configurado
            // Y solo la última factura por cliente para este router
            $latestInvoiceIds = Invoice::withoutGlobalScope('router_filter')
                ->selectRaw('MAX(id) as id')
                ->whereNotIn('status', ['paid', 'canceled'])
                ->whereRaw('DAY(issue_date) >= ?', [$billingDate])
                ->whereHas('customer', function ($q) use ($router) {
                    $q->where('router_id', $router->id);
                })
                ->groupBy('customer_id')
                ->pluck('id');

            $query = Invoice::withoutGlobalScope('router_filter')
                ->whereNotIn('status', ['paid', 'canceled'])
                ->whereRaw('DAY(issue_date) >= ?', [$billingDate])
                ->whereIn('id', $latestInvoiceIds)
                ->with('customer');

            $total = (clone $query)->count();
            $this->info("Facturas candidatas para router {$router->name}: {$total} (filtradas por issue_date >= día {$billingDate})");

            if ($total === 0) {
                continue;
            }

            // Procesar en lotes de máximo 100 facturas en paralelo
            $this->processInvoicesInBatches($query, $dryRun, $processed, $created, $resent, $errors);
        }

        $this->info("Procesadas: {$processed} | Creadas: {$created} | Reenviadas: {$resent} | Errores: {$errors}");

        return $errors > 0 ? self::FAILURE : self::SUCCESS;
    }

    /**
     * Procesa facturas en lotes de máximo 100 en paralelo
     */
    private function processInvoicesInBatches($query, bool $dryRun, int &$processed, int &$created, int &$resent, int &$errors): void
    {
        $handler = new OnePayHandler();
        $batchSize = 100;

        $query->orderBy('id')->chunk(200, function ($invoices) use (&$processed, &$created, &$resent, &$errors, $handler, $dryRun, $batchSize) {
            // Dividir el chunk en sublotes de máximo 100
            $batches = $invoices->chunk($batchSize);

            foreach ($batches as $batch) {
                // Recopilar todas las solicitudes a procesar en paralelo
                $invoicesToProcess = [];

                foreach ($batch as $invoice) {
                    $processed++;

                    if ($dryRun) {
                        if (!$invoice->onepay_charge_id) {
                            $this->line("[DRY-RUN] Crear cobro para factura #{$invoice->increment_id}");
                        } else {
                            $this->line("[DRY-RUN] Reenviar cobro para factura #{$invoice->increment_id} ({$invoice->onepay_charge_id})");
                        }
                        continue;
                    }

                    if (!$invoice->onepay_charge_id) {
                        $invoicesToProcess[] = $invoice;
                    }
                }

                // Procesar el lote en paralelo (si no es dry-run)
                if (!$dryRun && count($invoicesToProcess) > 0) {
                    $this->processBatchInParallel($invoicesToProcess, $handler, $created, $errors);
                }
            }
        });
    }

    /**
     * Procesa un lote de facturas en paralelo
     */
    private function processBatchInParallel(array $invoices, OnePayHandler $handler, int &$created, int &$errors): void
    {
        foreach ($invoices as $invoice) {
            try {
                // Asegura cliente y crea cobro; luego guarda en la factura
                $data = $handler->createPayment($invoice);
                $invoice->update([
                    'onepay_charge_id' => $data['id'] ?? null,
                    'onepay_payment_link' => $data['payment_link'] ?? null,
                    'onepay_status' => $data['status'] ?? 'pending',
                    'onepay_metadata' => $data,
                ]);
                $created++;
            } catch (\Throwable $e) {
                $errors++;
                Log::error('Error procesando factura para OnePay (command)', [
                    'invoice_id' => $invoice->id,
                    'message' => $e->getMessage(),
                ]);
                $this->error("Factura #{$invoice->increment_id}: " . $e->getMessage());
            }
        }
    }
}
