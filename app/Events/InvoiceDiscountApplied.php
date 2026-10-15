<?php

namespace App\Events;

use App\Models\Invoice\Invoice;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class InvoiceDiscountApplied
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @param float $discountAmount Taxable base reduced by the discount.
     * @param float $taxAmount      VAT reduced together with the base (0 if untaxed).
     */
    public function __construct(
        public Invoice $invoice,
        public float $discountAmount,
        public string $description = '',
        public float $taxAmount = 0.0
    ) {
    }
}
