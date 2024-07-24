<?php

namespace App\PaymentMethods;

class Wompi extends AbstractPaymentMethod
{
    const PATH = "payment/wompi/";
    private string $payment_code = "wompi";
    private string $component = "Wompi";


    public function getConfiguration(): array
    {
        return [
            'payment_code' => $this->payment_code,
            'payment_component' => $this->component,
            'image' => '/img/payments/wompi.svg'
        ];
    }

    protected function getPath(): string
    {
        return self::PATH;
    }
}
