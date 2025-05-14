<?php

namespace App\Listeners;

use App\Events\InvoiceItemsCreated;
use App\Models\Services\Service;
use App\Models\ServiceRule;
use Illuminate\Support\Facades\Auth;

class ApplyRuleInvoice
{
    public function handle(InvoiceItemsCreated $event): void
    {
        $invoice = $event->invoice;

        // Obtenemos todos los adjustments de tipo 'charge'
        $chargeAdjustments = $invoice->adjustments()
            ->where('kind', 'charge')
            ->get();

        foreach ($chargeAdjustments as $adjustment) {
            $service = $adjustment->source; // Esto devuelve el modelo relacionado: normalmente un Service

            if (!$service || !method_exists($service, 'rules')) {
                continue;
            }

            $rules = $service->rules()
                ->whereColumn('cycles_used', '<', 'cycles')
                ->get();

            foreach ($rules as $rule) {
                $discountAmount = match ($rule->type) {
                    'percentage' => $adjustment->amount * ($rule->value / 100),
                    'fixed'      => $rule->value,
                    'free_month' => $adjustment->amount,
                    default      => 0,
                };

                if ($discountAmount <= 0) {
                    continue;
                }

                // Crear item de descuento negativo
                $discountItem = $invoice->items()->create([
                    'description' => "Descuento aplicado: {$rule->type}",
                    'invoice_id'  => $invoice->id,
                    'unit_price'  => -$discountAmount,
                    'service_id'  => $service->id,
                    'quantity'    => 1,
                    'subtotal'    => -$discountAmount,
                ]);

                // Crear el adjustment que representa el descuento
                $invoice->adjustments()->create([
                    'kind'        => 'discount',
                    'amount'      => -$discountAmount,
                    'source_type' => ServiceRule::class,
                    'source_id'   => $rule->id,
                    'label'       => "Descuento por regla: {$rule->type}",
                    'metadata'    => [
                        'service_id'       => $service->id,
                        'invoice_item_id'  => $discountItem->id,
                        'rule_id'          => $rule->id,
                        'rule_type'        => $rule->type,
                    ],
                    'created_by' => Auth::id()??null,
                ]);

                $rule->increment('cycles_used');
            }
        }

        $invoice->recalcTotals();
    }
}
