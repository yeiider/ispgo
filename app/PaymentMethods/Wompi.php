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

    const FIELD_NAME = 'wompi-enabled';
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

    protected function getFiledEnabled(): string
    {
        return self::FIELD_NAME;
    }

    public static function getEnvironment(): string|null
    {
        return ConfigHelper::getConfigValue(self::PATH . 'env');
    }

    public static function getPublicKey(): string|null
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
            return "https://sandbox.wompi.co/v1/transactions/";
        }
        return "https://production.wompi.co/v1/transactions/";
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

        $expires_at = Carbon::now('UTC')->addDays(10)->format('Y-m-d\TH:i:s');

        $payload = [
            "name" => "Invoice",
            "description" => "Pago de factura",
            "single_use" => true,
            "collect_shipping" => false,
            "currency" => config('nova.currency'),
            "amount_in_cents" => intval($invoice->total) * 100,
            "expires_at" => $expires_at,
            "redirect_url" => self::getConfirmationUrl()."-link/".$invoice->increment_id,
            "image_url" => null,
            "sku" => $invoice->increment_id,
            "reference" => $invoice->increment_id
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

    /**
     * @throws \Exception
     */
    public static function getPaymentLink($invoice): ?string
    {
        // Verificar si ya existe un enlace de pago y si no ha expirado
        if ($invoice->payment_link && $invoice->expiration_date) {
            $currentDate = Carbon::now('UTC'); // Fecha y hora actual
            $expirationDate = Carbon::parse($invoice->expiration_date);

            if ($currentDate->lessThan($expirationDate)) {
                // Retornar el enlace actual si aún no ha expirado
                return "https://checkout.wompi.co/l/" . $invoice->payment_link;
            }
        }

        // Generar un nuevo enlace si no existe uno o si ya expiró
        $link = Wompi::generatedLinkPayment($invoice);

        if (isset($link['data']['id'])) {
            $expires_at = $link['data']['expires_at'];
            $invoice->payment_link = $link['data']['id'];
            $invoice->expiration_date = $expires_at;
            $invoice->save();

            return "https://checkout.wompi.co/l/" . $link['data']['id'];
        }

        return null; // Retorna null si no se puede generar un enlace de pago válido
    }
}
