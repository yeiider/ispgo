<?php

namespace App\Console\Commands;

use App\Models\Invoice\Invoice;
use App\Services\Payments\OnePay\OnePayHandler;
use App\Settings\OnePaySettings;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

/**
 * Red de seguridad del borrado de cobros OnePay.
 *
 * Recorre las facturas YA pagadas por un medio distinto a OnePay
 * (efectivo, transferencia, Nequi, PSE...) que todavía tengan una
 * factura/cobro creado en OnePay y lo elimina con
 * `DELETE /invoices/{id}` (reason=DELETE_FROM_PROVIDER).
 *
 * Sirve para limpiar lo que el listener automático
 * (CancelOnePayChargeOnExternalPayment) no alcanzó a borrar — cola caída,
 * job fallido, datos importados, etc. Es idempotente: volver a correrlo no
 * hace daño porque las facturas ya limpias pierden sus referencias OnePay.
 */
class OnePayCleanupPaidCobros extends Command
{
    protected $signature = 'onepay:cleanup-paid-cobros
                            {--dry-run : Solo listar lo que se eliminaría, sin borrar}
                            {--limit= : Máximo de facturas a procesar (0 = sin límite)}';

    protected $description = 'Elimina en OnePay la factura/cobro de facturas ya pagadas por un medio distinto a OnePay';

    public function handle(): int
    {
        if (!OnePaySettings::enabled()) {
            $this->warn('OnePay no está habilitado. Abortando.');
            return self::SUCCESS;
        }

        if (!OnePaySettings::baseUrl() || !OnePaySettings::apiToken()) {
            $this->error('Configuración de OnePay incompleta (base_url o api_token).');
            return self::FAILURE;
        }

        $dryRun = (bool) $this->option('dry-run');
        $limit  = (int) $this->option('limit');

        $handler = new OnePayHandler();

        $processed = 0;
        $deleted   = 0;
        $fallback  = 0;
        $errors    = 0;

        $query = Invoice::withoutGlobalScope('router_filter')
            ->where('status', 'paid')
            ->where(function ($q) {
                $q->whereNull('payment_method')
                  ->orWhere('payment_method', '!=', 'onepay');
            })
            ->where(function ($q) {
                $q->whereNotNull('onepay_invoice_id')
                  ->orWhereNotNull('onepay_charge_id');
            });

        $total = (clone $query)->count();
        $this->info("Facturas pagadas con referencias OnePay pendientes de limpiar: {$total}");

        if ($total === 0) {
            return self::SUCCESS;
        }

        if ($dryRun) {
            $this->warn('Modo --dry-run: no se borra nada.');
        }

        $query->chunkById(200, function ($invoices) use (&$processed, &$deleted, &$fallback, &$errors, $handler, $dryRun, $limit) {
            foreach ($invoices as $invoice) {
                if ($limit > 0 && $processed >= $limit) {
                    return false; // detiene el chunking
                }

                $processed++;

                if ($dryRun) {
                    $this->line("[DRY-RUN] Factura #{$invoice->increment_id} | onepay_invoice_id={$invoice->onepay_invoice_id} | onepay_charge_id={$invoice->onepay_charge_id}");
                    continue;
                }

                try {
                    $wasDeleted = $handler->deleteInvoiceForExternalPayment($invoice);

                    if ($wasDeleted) {
                        $deleted++;
                        $invoice->forceFill([
                            'onepay_invoice_id' => null,
                            'onepay_charge_id' => null,
                            'onepay_payment_link' => null,
                            'onepay_status' => 'deleted',
                        ])->save();
                    } else {
                        $fallback++;
                        Log::warning('OnePay cleanup: no se pudo resolver la factura, solo se intentó cancelar el cobro', [
                            'invoice_id' => $invoice->id,
                            'increment_id' => $invoice->increment_id,
                        ]);
                    }
                } catch (\Throwable $e) {
                    $errors++;
                    Log::error('OnePay cleanup error', [
                        'invoice_id' => $invoice->id,
                        'increment_id' => $invoice->increment_id,
                        'error' => $e->getMessage(),
                    ]);
                }

                // Pausa corta para no saturar la API de OnePay.
                usleep(100000); // 0.1s

                if ($processed % 100 === 0) {
                    $this->line("  ... {$processed} procesadas (eliminadas: {$deleted}, fallback: {$fallback}, errores: {$errors})");
                }
            }
        });

        $this->info("Listo. Procesadas: {$processed} | Facturas OnePay eliminadas: {$deleted} | Solo cobro cancelado: {$fallback} | Errores: {$errors}");

        return $errors > 0 ? self::FAILURE : self::SUCCESS;
    }
}
