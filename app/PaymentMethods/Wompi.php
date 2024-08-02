<?php

namespace App\PaymentMethods;

use App\Helpers\ConfigHelper;
use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;

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

    public static function getPrivateKey(): string
    {
        if (self::getEnvironment() === 'sandbox') {
            return ConfigHelper::getConfigValue(self::PATH . 'private_key_sandbox');
        }
        return ConfigHelper::getConfigValue(self::PATH . 'private_key');
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

    public static function getSecretEvents(): string
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

    public static function generatedLinkPayment($invoice)
    {
        $expires_at = Carbon::now('UTC')->addHours(3)->format('Y-m-d\TH:i:s');

        $payload = [
            "name" => "Invoice",
            "description" => "Pago de factura",
            "single_use" => false,
            "collect_shipping" => false,
            "currency" => config('nova.currency'),
            "amount_in_cents" => intval($invoice->total) * 100,
            "expires_at" => $expires_at,
            "redirect_url" => self::getConfirmationUrl(),
            "image_url" => null,
            "sku" => $invoice->increment_id,
        ];

        $client = new Client();
        $url = self::getEnvironment() === 'sandbox' ? 'https://sandbox.wompi.co/v1/payment_links' : 'https://production.wompi.co/v1/payment_links';

        try {
            $response = $client->post($url, [
                'headers' => [
                    'Authorization' => 'Bearer ' . self::getPrivateKey(),
                    'Content-Type' => 'application/json',
                ],
                'json' => $payload,
            ]);

            $responseBody = json_decode($response->getBody()->getContents(), true);
            return $responseBody;

        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                $errorResponse = json_decode($e->getResponse()->getBody()->getContents(), true);
                // Manejo de errores según la respuesta de Wompi
                throw $e;
            } else {
                throw new \Exception('Error al realizar la solicitud: ' . $e->getMessage());
            }
        } catch (GuzzleException $e) {
        }
    }
}
