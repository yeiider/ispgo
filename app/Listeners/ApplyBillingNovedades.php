<?php

namespace App\Listeners;

use App\Events\InvoiceCreatedBefore;
use App\Models\BillingNovedad;
use App\Models\InvoiceItem;
use Illuminate\Support\Facades\DB;

class ApplyBillingNovedades
{
    /**
     * Maneja el evento antes de persistir la factura
     */
    public function handle(InvoiceCreatedBefore $event): void
    {
        $invoice = $event->invoice;          // factura en construcción
        $service = $invoice->service;        // servicio al que pertenece
        $periodStart = $invoice->billing_period_start; // p.ej. 2025-05-01

        // 1) Traer todas las novedades PENDIENTES del servicio para ese ciclo
        $novedades = BillingNovedad::query()
            ->pending()
            ->forService($service->id)
            ->forPeriod($periodStart)
            ->get();


        if ($novedades->isEmpty()) {
            return; // nada que hacer
        }

        DB::transaction(function () use ($novedades, $invoice) {

            foreach ($novedades as $nov) {

                switch ($nov->type) {
                    // ——————————————————————————
                    // Entrega de producto (router, ONT…)
                    // ——————————————————————————
                    case BillingNovedad::T_ENTREGA_PRODUCTO:

                        foreach ($nov->product_lines as $line) {
                            InvoiceItem::create([
                                'invoice_id' => $invoice->id,
                                'item_id'    => $line['product_id'],
                                'quantity'   => $line['qty'],
                                'unit_price' => $line['unit_price'],
                                'total'      => $line['total'],
                            ]);
                        }
                        break;


                    // ——————————————————————————
                    // Cargos/descuentos genéricos
                    // ——————————————————————————
                    default:
                        InvoiceItem::create([
                            'invoice_id' => $invoice->id,
                            'item_id' => null,
                            'quantity' => 1,
                            'unit_price' => $nov->amount,
                            'total' => $nov->amount,
                            'description' => $nov->description ?? ucfirst(str_replace('_', ' ', $nov->type)),
                        ]);
                        break;
                }

                // 2) Sumar / restar monto al subtotal de la factura
                $invoice->subtotal += $nov->amount;

                // 3) Marcar la novedad como aplicada
                $nov->markAsApplied($invoice);
            }

            // 4) Recalcular impuestos / total
            $invoice->taxes = $invoice->calculateTaxes();   // tu helper interno
            $invoice->total = $invoice->subtotal + $invoice->taxes;
            $invoice->save(); // importante: persiste cambios dentro de la TX
        });
    }
}
