<?php

namespace App\Listeners;

use App\Events\InvoicePaid;
use App\Settings\OnePaySettings;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CancelOnePayChargeOnExternalPayment
{
    /**
     * Handle the event.
     */
    public function handle(InvoicePaid $event): void
    {
        $invoice = $event->invoice;

        // If payment did come from OnePay, do nothing
        if (($invoice->payment_method ?? null) === 'onepay') {
            return;
        }

        // If there is a pending OnePay charge, cancel it
        if ($invoice->onepay_charge_id) {
            try {
                $baseUrl = rtrim(OnePaySettings::baseUrl() ?? '', '/');
                $token = OnePaySettings::apiToken();
                if (!$baseUrl || !$token) {
                    Log::warning('OnePay settings missing; cannot cancel charge automatically.');
                } else {
                    $endpoint = $baseUrl . '/payments/' . $invoice->onepay_charge_id;
                    $response = Http::withToken($token)->delete($endpoint);
                    if (!$response->successful() && $response->status() !== 204) {
                        Log::warning('Failed to cancel OnePay charge after external payment', [
                            'status' => $response->status(),
                            'body' => $response->body(),
                        ]);
                    }
                }
            } catch (\Throwable $e) {
                Log::error('Error cancelling OnePay charge after external payment: ' . $e->getMessage());
            }

            // Clear local fields regardless to avoid dangling links
            $invoice->onepay_charge_id = null;
            $invoice->onepay_payment_link = null;
            $invoice->onepay_status = null;
            $invoice->onepay_metadata = null;
            $invoice->save();
        }
    }
}
