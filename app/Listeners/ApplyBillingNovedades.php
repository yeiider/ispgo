<?php

namespace App\Listeners;

use App\Events\InvoiceItemsCreated;
use App\Events\InvoicePaid;
use App\Models\BillingNovedad;
use App\Models\Inventory\Product;
use App\Models\Services\Service;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ApplyBillingNovedades
{
    public function handle(InvoiceItemsCreated $event): void
    {
        $invoice = $event->invoice;

        // No aplicar novedades a facturas manuales
        if ($invoice->invoice_type === 'manual' || $invoice->invoice_type === 'adjustment') {
            return;
        }

        $period  = $invoice->billing_period_start;

        // 1) Encontrar los servicios vinculados mediante charge-adjustments
        $services = $invoice->adjustments()
            ->where('kind', 'charge')
            ->get()
            ->map(fn ($adj) => $adj->source)
            ->filter(fn ($src) => $src instanceof Service)
            ->unique('id');

        if ($services->isEmpty()) {
            return;
        }

        DB::transaction(function () use ($services, $invoice, $period) {

            foreach ($services as $service) {

                $novedades = BillingNovedad::withoutGlobalScope('router_filter')
                    ->pending()
                    ->forService($service->id)
                    ->forPeriod($period)
                    ->get();

                foreach ($novedades as $nov) {

                    // ———————————————— A) Novedad por productos ————————————————
                    if ($nov->type === BillingNovedad::T_ENTREGA_PRODUCTO && $nov->product_lines) {

                        foreach ($nov->product_lines as $rawLine) {

                            $line  = $rawLine['fields'] ?? $rawLine;            // normalizar
                            $pid   = $line['product_id'] ?? $line['id'] ?? null;
                            $qty   = (int)($line['qty']   ?? 1);

                            // Saltar si no hay producto
                            if (!$pid || !$product = Product::find($pid)) {
                                continue;
                            }

                            $price = (float)($product->special_price ?? $product->price ?? 0.0);
                            $total = $price * $qty;

                            $item = $invoice->items()->create([
                                'description' => $product->name,     // nombre real
                                'invoice_id'  => $invoice->id,
                                'service_id'  => $service->id,
                                'item_id'     => $product->id,
                                'quantity'    => $qty,
                                'unit_price'  => $price,
                                'subtotal'    => $total,
                                'metadata'    => $line + ['product_name' => $product->name],
                            ]);

                            $this->createAdjustment($invoice, $nov, $item, 'charge', $total, $service);
                        }



                        // ———————————————— B) Otras novedades (cargos o descuentos) ————————————————
                    } else {
                        $item = $invoice->items()->create([
                            'description' => $nov->description ?? ucfirst(str_replace('_', ' ', $nov->type)),
                            'invoice_id'  => $invoice->id,
                            'service_id'  => $service->id,
                            'quantity'    => 1,
                            'unit_price'  => $nov->amount,
                            'subtotal'    => $nov->amount,
                        ]);

                        $kind = $nov->amount < 0 ? 'discount' : 'charge';
                        $this->createAdjustment($invoice, $nov, $item, $kind, $nov->amount, $service);
                    }

                    // 3) Marcar novedad como aplicada
                    $nov->markAsApplied($invoice);
                }
            }

            // 4) Recalcular totales
            $invoice->recalcTotals();

            // 5) Si el saldo pendiente quedó en 0 (ej: cubierto por saldo a favor),
            //    marcar la factura como pagada automáticamente.
            $invoice->refresh();

            if ($invoice->status !== 'paid' && $invoice->outstanding_balance <= 0) {
                $invoice->status         = 'paid';
                $invoice->payment_method = 'credit';   // saldo a favor / novedad
                $invoice->outstanding_balance = 0;
                $invoice->save();

                Log::info("Factura #{$invoice->increment_id} marcada como pagada automáticamente por saldo a favor.");

                event(new InvoicePaid($invoice));
            }
        });
    }

    /**
     * Crea un Adjustment asociado a la novedad y al servicio.
     */
    private function createAdjustment($invoice, BillingNovedad $nov, $item, string $kind, float $amount, $service): void
    {
        $invoice->adjustments()->create([
            'kind'        => $kind,
            'amount'      => $amount,
            'source_type' => get_class($nov),
            'source_id'   => $nov->id,
            'label'       => $nov->description ?? "Novedad: {$nov->type}",
            'metadata'    => [
                'service_id'      => $service->id,
                'invoice_item_id' => $item->id,
                'novedad_id'      => $nov->id,
                'novedad_type'    => $nov->type,
            ],
            'created_by'  => Auth::id(),
        ]);
    }
}
