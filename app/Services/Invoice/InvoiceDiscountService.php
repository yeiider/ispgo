<?php

namespace App\Services\Invoice;

use App\Events\InvoiceDiscountApplied;
use App\Models\Invoice\Invoice;
use App\Models\InvoiceAdjustment;
use App\Services\Billing\Tax\VatPolicy;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * Applies manual discounts to an invoice keeping base, VAT and total consistent.
 *
 * Rules:
 *  - A discount always reduces the taxable base; if the invoice carries VAT,
 *    the VAT is recalculated on the reduced base (fiscally correct and
 *    identical to what Siigo computes on the credit note).
 *  - $amountIncludesTax = true  -> the amount is gross (base + VAT).
 *  - $amountIncludesTax = false -> the amount is the base reduction; VAT drops on top.
 *  - Discount adjustments are stored as NEGATIVE amounts (same convention as
 *    recalcTotals() and ApplyRuleInvoice).
 */
class InvoiceDiscountService
{
    public const TAX_ADJUSTMENT_LABEL = 'Ajuste IVA por descuento';

    /**
     * Converts the user input (value or percentage) into a monetary amount.
     * Percentages apply to the total when the discount includes tax, and to
     * the subtotal (base) otherwise.
     */
    public function resolveAmount(Invoice $invoice, float $value, bool $isPercentage, bool $amountIncludesTax): float
    {
        if (!$isPercentage) {
            return round($value, 2);
        }

        $reference = $amountIncludesTax ? (float) $invoice->total : (float) $invoice->subtotal;

        return round($reference * ($value / 100), 2);
    }

    /**
     * @return array{base: float, tax: float, total: float}
     */
    public function apply(Invoice $invoice, float $amount, bool $amountIncludesTax, string $description = ''): array
    {
        if ($amount <= 0) {
            throw new \InvalidArgumentException(__('The discount must be greater than zero.'));
        }

        $currentTax = $this->currentTax($invoice);
        $isTaxed = $currentTax > 0;

        $base = ($amountIncludesTax && $isTaxed)
            ? VatPolicy::baseFromGross($amount)
            : round($amount, 2);

        $taxCut = $isTaxed ? min($currentTax, VatPolicy::taxFor($base)) : 0.0;

        if ($base - (float) $invoice->subtotal > 0.001) {
            throw new \DomainException(__('Discount cannot be greater than the subtotal of the invoice.'));
        }

        DB::transaction(function () use ($invoice, $base, $taxCut, $currentTax, $description) {
            $newTax = round($currentTax - $taxCut, 2);
            $newTotal = round((float) $invoice->total - $base - $taxCut, 2);

            $invoice->subtotal = round((float) $invoice->subtotal - $base, 2);
            $invoice->tax_total = $newTax;
            $invoice->tax = $newTax;
            $invoice->discount = round((float) $invoice->discount + $base, 2);
            $invoice->total = $newTotal;
            $invoice->outstanding_balance = max(0, round($newTotal - (float) $invoice->amount, 2));
            $invoice->save();

            $label = $description ?: 'Descuento manual';
            $createdBy = Auth::id();

            InvoiceAdjustment::create([
                'invoice_id' => $invoice->id,
                'kind'       => 'discount',
                'amount'     => -$base,
                'label'      => $label,
                'created_by' => $createdBy,
            ]);

            if ($taxCut > 0) {
                InvoiceAdjustment::create([
                    'invoice_id' => $invoice->id,
                    'kind'       => 'tax',
                    'amount'     => -$taxCut,
                    'label'      => self::TAX_ADJUSTMENT_LABEL,
                    'metadata'   => ['tax_rate' => VatPolicy::RATE, 'discount_base' => $base],
                    'created_by' => $createdBy,
                ]);
            }
        });

        event(new InvoiceDiscountApplied($invoice, $base, $description, $taxCut));

        return ['base' => $base, 'tax' => $taxCut, 'total' => round($base + $taxCut, 2)];
    }

    /**
     * Legacy invoices may hold VAT in `tax` instead of `tax_total`.
     */
    private function currentTax(Invoice $invoice): float
    {
        return max((float) ($invoice->tax_total ?? 0), (float) ($invoice->tax ?? 0));
    }
}
