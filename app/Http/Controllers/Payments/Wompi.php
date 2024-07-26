<?php

namespace App\Http\Controllers\Payments;

use App\Helpers\ConfigHelper;
use App\Http\Controllers\Controller;
use App\Models\Invoice\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class Wompi extends Controller
{

    public function confirmation(Request $request)
    {
        $id = $request->get("id");
        if (!$id) {
            session(['payment_data' => ["status" => "PENDING"]]);
            return redirect()->route('checkout.index');
        }
        $response = Http::get(\App\PaymentMethods\Wompi::getStatusUrl() . $id)->json();
        $data = \App\PaymentMethods\Wompi::processResponse($response);
        if ($data["status"] == "APPROVED") {
            $this->registerPaying($data);
        }
        session(['payment_data' => $data]);
        return redirect()->route('checkout.index');
    }

    public function response(Request $request)
    {
        // Aquí manejarás la respuesta del pago
    }

    public function signature(Request $request)
    {
        $request->validate([
            'reference' => 'required|string',
            'amount' => 'required|int',
        ]);

        $reference = $request->input('reference');
        $amount = $request->input('amount');
        $apikey = \App\PaymentMethods\Wompi::getIntegrity();
        $currency = "COP";

        // Generar la cadena a firmar
        $stringToSign = "$reference$amount$currency$apikey";

        // Generar la firma
        $signature = hash('sha256', $stringToSign);

        return response()->json([
            'signature' => $signature,
        ]);
    }

    /**
     * Handle Wompi Event.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function handleWompiEvent(Request $request): \Illuminate\Http\Response
    {
        $eventPayload = $request->all();
        $signature = $request->header('X-Event-Checksum');
        if (!$this->verifySignature($eventPayload, $signature)) {
            return response('Signature verification failed', 400);
        }

        $eventType = $eventPayload['event'];
        $eventData = $eventPayload['data'];

        switch ($eventType) {
            case 'transaction.updated':
                $this->registerPayingByEvents($eventData);
                break;
            default:
                break;
        }
        return response('Event received', 200);
    }

    protected function verifySignature(array $eventPayload, string $signature): bool
    {
        $eventSecret = \App\PaymentMethods\Wompi::getSecretEvents();
        $transactions = $eventPayload['data']['transactions'];
        $transactionId = $transactions['id'];
        $status = $transactions['status'];
        $amountInCents = $transactions['amount_in_cents'];

        $payloadSignature = "$transactionId$status$amountInCents$eventSecret";
        $calculatedSignature = hash('sha256', $payloadSignature);
        return hash_equals($calculatedSignature, $signature);
    }

    private function registerPaying(array $data): void
    {
        $invoice = Invoice::where("increment_id", $data["reference"])->first();
        $amount = $data["amount"];
        $invoice->applyPayment($amount, 'wompi', $data);
    }

    private function registerPayingByEvents(mixed $eventData): void
    {
        $transaction = $eventData['transaction'];
        $invoice = Invoice::where("increment_id", $eventData["reference"])->first();
        $amount = $transaction["amount_in_cents"] / 100;
        $invoice->applyPayment($amount, 'wompi', $eventData);
    }
}
