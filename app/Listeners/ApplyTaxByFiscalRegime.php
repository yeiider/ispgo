<?php

namespace App\Listeners;

use App\Events\InvoiceItemsCreated;
use App\Models\InvoiceAdjustment;
use App\Services\Billing\Tax\VatPolicy;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

/**
 * Aplica automáticamente el IVA (19%) a facturas de clientes que
 * son responsables de IVA según VatPolicy (la misma regla que usa Siigo).
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
     * @deprecated Usa VatPolicy::RATE.
     */
    const IVA_RATE = VatPolicy::RATE;

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
            !VatPolicy::isTaxDetailVatResponsible($taxDetail)
        ) {
            return;
        }

        // Recalcular subtotal fresco desde la base de datos
        $invoice->load('adjustments');

        // Verificar que no se haya aplicado ya un ajuste de impuesto automático
        $alreadyApplied = $invoice->adjustments()
            ->where('kind', 'tax')
            ->where('label', VatPolicy::ADJUSTMENT_LABEL)
            ->exists();

        if ($alreadyApplied) {
            Log::info("ApplyTaxByFiscalRegime: IVA ya aplicado a factura #{$invoice->id}, omitiendo.");
            return;
        }

        // Calcular la base gravable sumando los cargos gravables
        $taxableSubtotal = 0;
        foreach ($invoice->adjustments->where('kind', 'charge') as $adj) {
            $isTaxable = true;
            $meta = $adj->metadata ?? [];

            if (isset($meta['is_taxable'])) {
                $isTaxable = (bool) $meta['is_taxable'];
            } elseif (!empty($meta['additional_plan_id'])) {
                $ap = \App\Models\Services\AdditionalPlan::find($meta['additional_plan_id']);
                if ($ap) {
                    $isTaxable = (bool) $ap->is_taxable;
                }
            } elseif ($adj->source_type === \App\Models\Services\AdditionalPlan::class && $adj->source_id) {
                $ap = \App\Models\Services\AdditionalPlan::find($adj->source_id);
                if ($ap) {
                    $isTaxable = (bool) $ap->is_taxable;
                }
            }

            if ($isTaxable) {
                $taxableSubtotal += $adj->amount;
            }
        }

        // Restar descuentos si aplican
        $discounts = $invoice->adjustments->where('kind', 'discount')->sum('amount');
        $taxableSubtotal = max(0, $taxableSubtotal + $discounts);

        if ($taxableSubtotal <= 0) {
            return;
        }

        $ivaAmount = VatPolicy::taxFor($taxableSubtotal);

        if ($ivaAmount <= 0) {
            return;
        }

        // Crear el ajuste de tipo "tax" en la factura
        $invoice->adjustments()->create([
            'kind'       => 'tax',
            'amount'     => $ivaAmount,
            'label'      => VatPolicy::ADJUSTMENT_LABEL,
            'metadata'   => [
                'auto_generated' => true,
                'fiscal_regime'  => $taxDetail->fiscal_regime,
                'tax_rate'       => VatPolicy::RATE,
            ],
            'created_by' => null,
        ]);

        // Recalcular totales para que Total = Subtotal + IVA
        $invoice->recalcTotals();

        Log::info("ApplyTaxByFiscalRegime: IVA de \${$ivaAmount} aplicado a factura #{$invoice->id} (cliente #{$customer->id}).");
    }
}
