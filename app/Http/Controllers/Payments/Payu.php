<?php

namespace App\Http\Controllers\Payments;

use App\Helpers\ConfigHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class Payu extends Controller
{
    const PATH = "payment/payu/";

    public function confirmation(Request $request)
    {
        // Aquí manejarás la confirmación de la transacción
    }

    public function response(Request $request)
    {
        // Aquí manejarás la respuesta del pago
    }

    public function signature(Request $request)
    {
        $request->validate([
            'reference' => 'required|string',
            'amount' => 'required|string',
        ]);

        $reference = $request->input('reference');
        $amount = $request->input('amount');
        $apikey = ConfigHelper::getConfigValue(self::PATH . 'api_key');
        $merchantId = ConfigHelper::getConfigValue(self::PATH . 'merchant_id');
        $currency = "COP";

        // Generar la cadena a firmar
        $stringToSign = "$apikey~$merchantId~$reference~$amount~$currency";

        // Generar la firma
        $signature = hash_hmac('sha256', $stringToSign, $apikey);

        return response()->json([
            'signature' => $signature,
        ]);
    }
}
