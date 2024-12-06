<?php

// app/PaymentMethods/PayU.php

namespace App\PaymentMethods;

use App\Helpers\ConfigHelper;

class Payu extends AbstractPaymentMethod
{
    const PATH = "payment/payu/";

    const FIELD_NAME = 'payu-enabled';
    const PRODUCTION_ACTION = "https://checkout.payulatam.com/ppp-web-gateway-payu/";
    const SANDBOX_ACTION = "https://sandbox.checkout.payulatam.com/ppp-web-gateway-payu/";
    private string $payment_code = "payu";
    private string $component = "Payu";

    protected function getPath(): string
    {
        return self::PATH;
    }

    public function getConfiguration(): array
    {
        return [
            'action_url' => ConfigHelper::getConfigValue(self::PATH . 'env') === 'sandbox' ? self::SANDBOX_ACTION : self::PRODUCTION_ACTION,
            'url_confirmation' => ConfigHelper::getConfigValue(self::PATH . 'url_confirmation') ?? '',
            'url_response' => ConfigHelper::getConfigValue(self::PATH . 'url_response') ?? '',
            'merchant_id' => ConfigHelper::getConfigValue(self::PATH . 'merchant_id') ?? '',
            'account_id' => ConfigHelper::getConfigValue(self::PATH . 'account_id') ?? '',
            'payment_code' => $this->payment_code,
            'payment_component' => $this->component,
            'image' => '/img/payments/payu.svg',
        ];
    }

    protected function getFiledEnabled(): string
    {
        return self::FIELD_NAME;
    }
}

