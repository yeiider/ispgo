<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customers\Customer;
use App\Models\Invoice\Invoice;
use App\Services\Payments\OnePay\OnePayHandler;
use App\PaymentMethods\Wompi;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class McpController extends Controller
{
    /**
     * GET /api/mcp/customer/{identifier}
     * identifier: customer id or identity_document
     */
    public function getCustomer(string $identifier): JsonResponse
    {
        $customer = $this->findCustomerByIdentifier($identifier);
        if (!$customer) {
            return response()->json(['message' => 'Cliente no encontrado'], 404);
        }

        $services = $customer->services()->with('plan')->get()->map(function ($svc) {
            return [
                'ip' => $svc->service_ip,
                'status' => $svc->service_status,
                'sn' => $svc->sn,
                'mac' => $svc->mac_address,
                'plan_name' => optional($svc->plan)->name,
                'plan_price' => optional($svc->plan)->monthly_price,
            ];
        });

        return response()->json([
            'customer' => [
                'id' => $customer->id,
                'full_name' => $customer->full_name,
                'identity_document' => $customer->identity_document,
            ],
            'services' => $services,
        ]);
    }

    /**
     * GET /api/mcp/invoices/{identifier}/unpaid
     */
    public function getUnpaidInvoices(string $identifier): JsonResponse
    {
        $customer = $this->findCustomerByIdentifier($identifier);
        if (!$customer) {
            return response()->json(['message' => 'Cliente no encontrado'], 404);
        }

        $invoices = $customer->invoices()
            ->where('status', 'unpaid')
            ->orderByDesc('id')
            ->get()
            ->map(function (Invoice $inv) {
                return [
                    'id' => $inv->id,
                    'increment_id' => $inv->increment_id,
                    'total' => $inv->total,
                    'outstanding_balance' => $inv->outstanding_balance,
                    'due_date' => optional($inv->due_date)->toDateString(),
                    'status' => $inv->status,
                ];
            });

        return response()->json([
            'customer' => [
                'id' => $customer->id,
                'full_name' => $customer->full_name,
                'identity_document' => $customer->identity_document,
            ],
            'unpaid_invoices' => $invoices,
        ]);
    }

    /**
     * POST /api/mcp/payments
     * body: { invoice: (id or increment_id), method: onepay|wompi, action?: resend }
     */
    public function postPayment(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'invoice' => 'required',
            'method' => 'required|in:onepay,wompi',
            'action' => 'nullable|in:create,resend',
        ]);

        $invoice = $this->findInvoiceByIdentifier($validated['invoice']);
        if (!$invoice) {
            return response()->json(['message' => 'Factura no encontrada'], 404);
        }
        if ($invoice->status === Invoice::STATUS_PAID) {
            return response()->json(['message' => 'La factura ya fue pagada'], 409);
        }

        $action = $validated['action'] ?? 'create';
        $method = $validated['method'];

        try {
            if ($method === 'onepay') {
                $handler = new OnePayHandler();
                if ($action === 'resend') {
                    if (!$invoice->onepay_charge_id) {
                        return response()->json(['message' => 'La factura no tiene cobro OnePay para reenviar'], 422);
                    }
                    $handler->resendPayment($invoice);
                    return response()->json([
                        'method' => 'onepay',
                        'action' => 'resend',
                        'onepay_charge_id' => $invoice->onepay_charge_id,
                        'payment_link' => $invoice->onepay_payment_link,
                        'status' => $invoice->onepay_status,
                    ]);
                }

                // create or recreate
                $resp = $handler->createPayment($invoice);

                // Try to capture common fields from OnePay response
                $chargeId = $resp['id'] ?? ($resp['data']['id'] ?? null);
                $paymentLink = $resp['payment_link'] ?? $resp['checkout_url'] ?? $resp['url'] ?? ($resp['data']['url'] ?? null);
                $status = $resp['status'] ?? ($resp['data']['status'] ?? null);

                $invoice->onepay_charge_id = $chargeId;
                $invoice->onepay_payment_link = $paymentLink;
                $invoice->onepay_status = $status;
                $invoice->onepay_metadata = $resp;
                $invoice->save();

                return response()->json([
                    'method' => 'onepay',
                    'action' => 'create',
                    'onepay_charge_id' => $chargeId,
                    'payment_link' => $paymentLink,
                    'status' => $status,
                ]);
            }

            // WOMPI
            $link = Wompi::getPaymentLink($invoice);
            return response()->json([
                'method' => 'wompi',
                'payment_link' => $link,
                'reference' => $invoice->increment_id,
            ]);
        } catch (\Throwable $e) {
            Log::warning('MCP payment error', [
                'invoice_id' => $invoice->id ?? null,
                'error' => $e->getMessage(),
            ]);
            return response()->json([
                'message' => 'No se pudo procesar el pago',
                'error' => $e->getMessage(),
            ], 422);
        }
    }

    private function findCustomerByIdentifier(string $identifier): ?Customer
    {

        return Customer::where('identity_document', $identifier)->first();
    }

    private function findInvoiceByIdentifier($identifier): ?Invoice
    {
        if (is_numeric($identifier)) {
            // try id first
            $invoice = Invoice::find((int)$identifier);
            if ($invoice) return $invoice;
        }
        // fallback to increment_id
        return Invoice::where('increment_id', (string)$identifier)->first();
    }
}
