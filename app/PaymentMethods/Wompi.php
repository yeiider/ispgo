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
        return ConfigHelper::getConfigValue(self::PATH . 'confirmation_url');
    }

    public static function getSecretEvents():string
    {
        if (self::getEnvironment() === 'sandbox') {
            return ConfigHelper::getConfigValue(self::PATH . 'event_secret_sandbox');
        }
        return ConfigHelper::getConfigValue(self::PATH . 'event_secret');
    }

    public static function processResponse(array $response): array
    {
        $data = $response['data'] ?? [];
        return [
            'transaction_id' => $data['id'] ?? null,
            'created_at' => $data['created_at'] ?? null,
            'finalized_at' => $data['finalized_at'] ?? null,
            'amount' => $data['amount_in_cents'] / 100 ?? null, // Convertir de centavos a unidades monetarias
            'currency' => $data['currency'] ?? null,
            'status' => $data['status'] ?? null,
            'payment_method_type' => $data['payment_method_type'] ?? null,
            'reference' => $data['reference'] ?? null,
            'payment_description' => $data['payment_method']['payment_description'] ?? null,
            'merchant' => [
                'id' => $data['merchant']['id'] ?? null,
                'name' => $data['merchant']['name'] ?? null,
                'legal_name' => $data['merchant']['legal_name'] ?? null,
                'contact_name' => $data['merchant']['contact_name'] ?? null,
                'phone_number' => $data['merchant']['phone_number'] ?? null,
                'email' => $data['merchant']['email'] ?? null,
            ],
            'redirect_url' => $data['redirect_url'] ?? null,
        ];
    }
}
