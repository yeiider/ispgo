<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customers\Customer;
use App\Models\Invoice\Invoice;
use App\PaymentMethods\Wompi;
use App\Services\Payments\OnePay\OnePayHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * Public (no-auth) payment portal endpoints.
 * Used by the client-facing /pagar page: customers look themselves up
 * by document number or invoice number and get a payment link.
 */
class PublicPaymentController extends Controller
{
    /**
     * GET /api/public/payment/search?q={document|increment_id}
     * Resolves the term as a customer identity_document first, then as an invoice increment_id.
     * Returns only the fields needed for the payment UX (no address/phone/email).
     */
    public function search(Request $request): JsonResponse
    {
        $term = trim((string) $request->query('q', ''));

        if ($term === '') {
            return response()->json(['error' => 'Ingresa tu número de documento o de factura'], 422);
        }

        $customer = Customer::where('identity_document', $term)->first();

        if ($customer) {
            $invoices = $customer->invoices()
                ->where('status', 'unpaid')
                ->orderByDesc('id')
                ->get();
        } else {
            $invoice = Invoice::where('increment_id', $term)->first();

            if (!$invoice) {
                return response()->json(['error' => 'No encontramos facturas pendientes con ese dato'], 404);
            }

            $customer = $invoice->customer;
            $invoices = collect([$invoice]);
        }

        if (!$customer) {
            return response()->json(['error' => 'No encontramos facturas pendientes con ese dato'], 404);
        }

        return response()->json([
            'customer' => [
                'id' => $customer->id,
                'full_name' => $customer->full_name,
            ],
            'invoices' => $invoices->map(fn (Invoice $inv) => [
                'id' => $inv->id,
                'increment_id' => $inv->increment_id,
                'total' => (float) $inv->total,
                'outstanding_balance' => (float) $inv->outstanding_balance,
                'due_date' => optional($inv->due_date)->toDateString(),
                'status' => $inv->status,
            ])->values()->all(),
        ]);
    }

    /**
     * POST /api/public/payment/link
     * body: { invoice: increment_id, method: onepay|wompi }
     */
    public function link(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'invoice' => 'required|string',
            'method' => 'required|in:onepay,wompi',
        ]);

        $invoice = Invoice::where('increment_id', $validated['invoice'])->first();

        if (!$invoice) {
            return response()->json(['message' => 'Factura no encontrada'], 404);
        }

        if ($invoice->status === Invoice::STATUS_PAID) {
            return response()->json(['message' => 'La factura ya fue pagada'], 409);
        }

        $method = $validated['method'];

        try {
            if ($method === 'onepay') {
                $handler = new OnePayHandler();
                $resp = $handler->createPayment($invoice);

                $data = isset($resp['data']) && is_array($resp['data']) ? $resp['data'] : $resp;

                $invoiceId = $data['id'] ?? null;
                $chargeId = $data['payment_id'] ?? $data['payment']['id'] ?? null;
                $paymentLink = $data['payment']['payment_link'] ?? $data['payment_link'] ?? null;
                $status = $data['status'] ?? 'pending';

                $invoice->forceFill([
                    'onepay_invoice_id' => $invoiceId,
                    'onepay_charge_id' => $chargeId,
                    'onepay_payment_link' => $paymentLink,
                    'onepay_status' => $status,
                    'onepay_metadata' => $resp,
                ])->save();

                if (!$paymentLink) {
                    return response()->json([
                        'method' => 'onepay',
                        'payment_link' => null,
                        'reference' => $invoice->increment_id,
                        'status' => $status,
                        'message' => 'Cobro creado. El enlace de pago llegará a tu WhatsApp/email.',
                    ]);
                }

                return response()->json([
                    'method' => 'onepay',
                    'payment_link' => $paymentLink,
                    'reference' => $invoice->increment_id,
                    'status' => $status,
                ]);
            }

            // wompi
            $link = Wompi::getPaymentLink($invoice);

            if (!$link) {
                return response()->json([
                    'message' => 'No se pudo generar el enlace de pago de Wompi. Verifica la configuración.',
                ], 422);
            }

            return response()->json([
                'method' => 'wompi',
                'payment_link' => $link,
                'reference' => $invoice->increment_id,
            ]);
        } catch (\Throwable $e) {
            Log::warning('Public payment link error', [
                'invoice' => $validated['invoice'],
                'method' => $method,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'message' => 'No se pudo procesar el pago',
                'error' => $e->getMessage(),
            ], 422);
        }
    }
}
