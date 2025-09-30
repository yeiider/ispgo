<?php

namespace App\Http\Controllers\Webhooks;

use App\Events\InvoicePaid;
use App\Http\Controllers\Controller;
use App\Models\Invoice\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class OnePayWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $payload = $request->all();
        Log::info('OnePay webhook received', $payload);

        // Try to find payment/charge id from common keys
        $paymentId = $payload['id'] ?? $payload['payment_id'] ?? $payload['data']['id'] ?? null;
        $status = $payload['status'] ?? $payload['data']['status'] ?? null;

        if (!$paymentId) {
            return response()->json(['message' => 'payment id not found'], 400);
        }

        /** @var Invoice|null $invoice */
        $invoice = Invoice::where('onepay_charge_id', $paymentId)->first();
        if (!$invoice) {
            return response()->json(['message' => 'invoice not found'], 404);
        }

        if ($status) {
            $invoice->onepay_status = $status;
        }
        // Store full payload for traceability
        $invoice->onepay_metadata = $payload;
        $invoice->save();

        // If paid, dispatch domain event
        if ($status && in_array(Str::lower($status), ['paid', 'approved', 'success'])) {
            // mark payment method for listeners to infer source
            $invoice->payment_method = 'onepay';
            $invoice->status = 'paid';
            $invoice->save();
            event(new InvoicePaid($invoice));
        }

        return response()->json(['ok' => true]);
    }
}
