<?php

namespace App\Http\Controllers;

namespace App\Http\Controllers;

use App\PaymentMethods\PaymentMethodManager;

class PaymentController extends Controller
{
    public function getPaymentConfigurations(): \Illuminate\Http\JsonResponse
    {
        $paymentManager = new PaymentMethodManager();
        $configurations = $paymentManager->getEnabledMethods();

        return response()->json($configurations);
    }
}
