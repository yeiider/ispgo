<?php

namespace App\PaymentMethods;

use App\Helpers\ConfigHelper;

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
            'public_key' => $this->getPublicKey(),
            'confirmation_url' => $this->getConfirmationUrl(),
            'image' => '/img/payments/wompi.svg',
        ];
    }

    protected function getPath(): string
    {
        return self::PATH;
    }

    public static function getEnvironment(): string
    {
        return ConfigHelper::getConfigValue(self::PATH . 'env');
    }

    public static function getPublicKey(): string
    {
        if (self::getEnvironment() === 'sandbox') {
            return ConfigHelper::getConfigValue(self::PATH . 'public_key_sandbox');
        }
        return ConfigHelper::getConfigValue(self::PATH . 'public_key');
    }

    public static function getIntegrity(): string
    {
        if (self::getEnvironment() === 'sandbox') {
            return ConfigHelper::getConfigValue(self::PATH . 'integrity_sandbox');
        }
        return ConfigHelper::getConfigValue(self::PATH . 'integrity');
    }

    public static function getStatusUrl(): string
    {
        if (self::getEnvironment() === 'sandbox') {
            return ConfigHelper::getConfigValue(self::PATH . 'url_status_sandbox');
        }
        return ConfigHelper::getConfigValue(self::PATH . 'url_status');
    }

    public static function getConfirmationUrl(): ?string
    {
       return  ConfigHelper::getConfigValue(self::PATH . 'confirmation_url');
    }
}
