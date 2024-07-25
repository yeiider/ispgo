<?php

namespace App\Http\Controllers\Payments;

use App\Helpers\ConfigHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class Wompi extends Controller
{

    public function confirmation(Request $request)
    {
        $id = $request->get("id");
        $response = Http::get(\App\PaymentMethods\Wompi::getConfirmationUrl() . $id)->json();
        $data = $response["data"];
        session(['payment_data' => $data]);
        return redirect()->route('payment.confirmation');
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
}
