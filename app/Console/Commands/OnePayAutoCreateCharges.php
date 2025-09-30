<?php

namespace App\Console\Commands;

use App\Models\Invoice\Invoice;
use App\Services\Payments\OnePay\OnePayHandler;
use App\Settings\OnePaySettings;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class OnePayAutoCreateCharges extends Command
{
    protected $signature = 'onepay:auto-create-charges {--force : Run regardless of configured day} {--dry-run : Only show what would be processed}';

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
        $configDay = OnePaySettings::autoCreateDay();
        $forced = (bool) $this->option('force');

        if (!$forced) {
            if (!$configDay) {
                $this->info('onepay_auto_create_day no está definido. No se ejecuta.');
                return self::SUCCESS;
            }
            if ($todayDay !== (int) $configDay) {
                $this->info("Hoy es día {$todayDay}. Configurado para el día {$configDay}. Se omite ejecución.");
                return self::SUCCESS;
            }
        } else {
            $this->warn('Ejecución forzada --force: ignorando día configurado.');
        }

        $dryRun = (bool) $this->option('dry-run');
        $handler = new OnePayHandler();

        $processed = 0;
        $created = 0;
        $resent = 0;
        $errors = 0;

        $this->info('Iniciando procesamiento de facturas no pagadas...');

        // Selecciona todas salvo las pagadas/canceladas
        $query = Invoice::query()
            ->whereNotIn('status', ['paid', 'canceled'])
            ->with('customer');

        $total = (clone $query)->count();
        $this->info("Facturas candidatas: {$total}");

        $query->orderBy('id')->chunk(200, function ($invoices) use (&$processed, &$created, &$resent, &$errors, $handler, $dryRun) {
            foreach ($invoices as $invoice) {
                $processed++;
                try {
                    if ($dryRun) {
                        if (!$invoice->onepay_charge_id) {
                            $this->line("[DRY-RUN] Crear cobro para factura #{$invoice->increment_id}");
                        } else {
                            $this->line("[DRY-RUN] Reenviar cobro para factura #{$invoice->increment_id} ({$invoice->onepay_charge_id})");
                        }
                        continue;
                    }

                    if (!$invoice->onepay_charge_id) {
                        // Asegura cliente y crea cobro; luego guarda en la factura
                        $data = $handler->createPayment($invoice);
                        $invoice->update([
                            'onepay_charge_id' => $data['id'] ?? null,
                            'onepay_payment_link' => $data['payment_link'] ?? null,
                            'onepay_status' => $data['status'] ?? 'pending',
                            'onepay_metadata' => $data,
                        ]);
                        $created++;
                    } else {
                        // Reenviar notificación
                        $handler->resendPayment($invoice);
                        $resent++;
                    }
                } catch (\Throwable $e) {
                    $errors++;
                    Log::error('Error procesando factura para OnePay (command)', [
                        'invoice_id' => $invoice->id,
                        'message' => $e->getMessage(),
                    ]);
                    $this->error("Factura #{$invoice->increment_id}: " . $e->getMessage());
                }
            }
        });

        $this->info("Procesadas: {$processed} | Creadas: {$created} | Reenviadas: {$resent} | Errores: {$errors}");

        return $errors > 0 ? self::FAILURE : self::SUCCESS;
    }
}
