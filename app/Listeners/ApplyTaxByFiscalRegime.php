<?php

namespace App\Listeners;

use App\Events\InvoiceItemsCreated;
use App\Models\InvoiceAdjustment;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

/**
 * Aplica automáticamente el IVA (19%) a facturas de clientes que
 * tienen detalle de impuesto con régimen fiscal "general" (Régimen Común).
 *
 * Este listener se dispara tras el evento InvoiceItemsCreated, lo que
 * garantiza que el subtotal ya esté calculado antes de aplicar el impuesto.
 */
class ApplyTaxByFiscalRegime implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Cola donde se ejecuta el job.
     */
    public string $queue = 'redis';

    /**
     * Número de reintentos ante fallo.
     */
    public int $tries = 3;

    /**
     * Tasa de IVA colombiana estándar (19%).
     */
    const IVA_RATE = 0.19;

    /**
     * Código del régimen fiscal que aplica IVA (Régimen Común / General).
     * Ajusta este valor según el `code` que tenga tu tabla `fiscal_regimes`.
     */
    const TAXABLE_REGIME_CODE = 'general';

    /**
     * Handle the event.
     */
    public function handle(InvoiceItemsCreated $event): void
    {
        $invoice = $event->invoice;

        // Cargar el cliente con su detalle de impuesto (hasOne)
        $customer = $invoice->customer()->with('taxDetails')->first();

        if (!$customer) {
            return;
        }

        /** @var \App\Models\Customers\TaxDetail|null $taxDetail */
        $taxDetail = $customer->taxDetails; // hasOne → single model or null

        // Verificar que el cliente tiene detalle de impuesto configurado,
        // que tiene habilitada la facturación y que pertenece al régimen
        // que genera obligación de IVA.
        if (
            !$taxDetail ||
            !$taxDetail->enable_billing ||
            strtolower($taxDetail->fiscal_regime) !== self::TAXABLE_REGIME_CODE
        ) {
            return;
        }

        // Recalcular subtotal fresco desde la base de datos
        $invoice->load('adjustments');
        $subtotal = $invoice->subtotal ?? 0;

        if ($subtotal <= 0) {
            return;
        }

        // Verificar que no se haya aplicado ya un ajuste de impuesto automático
        $alreadyApplied = $invoice->adjustments()
            ->where('kind', 'tax')
            ->where('label', 'IVA 19%')
            ->exists();

        if ($alreadyApplied) {
            Log::info("ApplyTaxByFiscalRegime: IVA ya aplicado a factura #{$invoice->id}, omitiendo.");
            return;
        }

        $ivaAmount = round($subtotal * self::IVA_RATE, 2);

        if ($ivaAmount <= 0) {
            return;
        }

        // Crear el ajuste de tipo "tax" en la factura
        $invoice->adjustments()->create([
            'kind'       => 'tax',
            'amount'     => $ivaAmount,
            'label'      => 'IVA 19%',
            'metadata'   => [
                'auto_generated' => true,
                'fiscal_regime'  => $taxDetail->fiscal_regime,
                'tax_rate'       => self::IVA_RATE,
            ],
            'created_by' => null,
        ]);

        // Recalcular totales para que Total = Subtotal + IVA
        $invoice->recalcTotals();

        Log::info("ApplyTaxByFiscalRegime: IVA de \${$ivaAmount} aplicado a factura #{$invoice->id} (cliente #{$customer->id}).");
    }
}
