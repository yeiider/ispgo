<?php

namespace App\Services\Payments\OnePay;

use App\Models\Invoice\Invoice;
use App\Models\Customers\Customer;
use App\Settings\OnePaySettings;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class OnePayHandler
{
    protected string $baseUrl;
    protected string $token;

    public function __construct()
    {
        $this->baseUrl = rtrim(OnePaySettings::baseUrl() ?? '', '/');
        $this->token = (string) OnePaySettings::apiToken();
    }

    public function ensureCustomerId(Customer $customer): ?string
    {
        if (!empty($customer->onepay_customer_id)) {
            return $customer->onepay_customer_id;
        }

        $payload = $this->buildCustomerPayload($customer);

        $endpoint = $this->baseUrl . '/customers';
        $response = Http::timeout(30)
            ->withToken($this->token)
            ->acceptJson()
            ->asJson()
            ->withHeaders([
                'x-idempotency' => $this->idempotencyKey('customer', (string)$customer->id),
            ])
            ->post($endpoint, $payload);

        if (!$response->successful()) {
            $msg = $this->extractErrorMessage($response);
            Log::warning('OnePay create customer failed', [
                'customer_id' => $customer->id,
                'status' => $response->status(),
                'error' => $msg,
            ]);
            throw new \Exception("Error al crear cliente en OnePay: {$msg}");
        }

        $data = $response->json();
        $onepayCustomerId = $data['id'] ?? null;
        if ($onepayCustomerId) {
            // Update only this attribute to avoid touching timestamps in some flows
            $customer->forceFill(['onepay_customer_id' => $onepayCustomerId])->save();
        }

        return $onepayCustomerId;
    }

    public function createPayment(Invoice $invoice): array
    {
        $payload = $this->buildPaymentPayload($invoice);
        $endpoint = $this->baseUrl . '/payments';

        Log::info('OnePay creating payment', [
            'invoice_id' => $invoice->id,
            'payload' => $payload,
        ]);

        $response = Http::timeout(30)
            ->withToken($this->token)
            ->acceptJson()
            ->asJson()
            ->withHeaders([
                'x-idempotency' => $this->idempotencyKey('payment_create', (string)$invoice->id),
            ])
            ->post($endpoint, $payload);

        if (!$response->successful()) {
            $msg = $this->extractErrorMessage($response);
            Log::warning('OnePay create payment failed', [
                'invoice_id' => $invoice->id,
                'status' => $response->status(),
                'error' => $msg,
            ]);
            throw new \Exception("Error al crear cobro OnePay: {$msg}");
        }

        return (array) $response->json();
    }

    public function resendPayment(Invoice $invoice): void
    {
        if (!$invoice->onepay_charge_id) {
            throw new \InvalidArgumentException('La factura no tiene un cobro de OnePay asociado');
        }
        $endpoint = $this->baseUrl . '/payments/' . $invoice->onepay_charge_id;
        $response = Http::timeout(30)
            ->withToken($this->token)
            ->acceptJson()
            ->asJson()
            ->withHeaders([
                'x-idempotency' => $this->idempotencyKey('payment_resend', (string)$invoice->id),
            ])
            ->post($endpoint);

        if (!$response->successful()) {
            $msg = $this->extractErrorMessage($response);
            Log::warning('OnePay resend payment failed', [
                'invoice_id' => $invoice->id,
                'status' => $response->status(),
                'error' => $msg,
            ]);
            throw new \Exception("Error al reenviar cobro OnePay: {$msg}");
        }
    }

    public function deletePayment(string $paymentId): void
    {
        $endpoint = $this->baseUrl . '/payments/' . $paymentId;
        $response = Http::timeout(30)
            ->withToken($this->token)
            ->acceptJson()
            ->delete($endpoint);

        if (!$response->successful() && $response->status() !== 204) {
            $msg = $this->extractErrorMessage($response);
            throw new \Exception("Error al eliminar cobro OnePay: {$msg}");
        }
    }

    public function buildPaymentPayload(Invoice $invoice): array
    {
        if (!$invoice->total || $invoice->total <= 0) {
            throw new \Exception("La factura #{$invoice->increment_id} no tiene un monto válido");
        }
        if (!$invoice->customer) {
            throw new \Exception("La factura #{$invoice->increment_id} no tiene cliente asociado");
        }

        $customer = $invoice->customer;
       // $onepayCustomerId = $this->ensureCustomerId($customer);

        $amountInCents = (int) $invoice->total; // si ya viene en centavos, ajustar aquí
        $taxInCents = (int) ($invoice->tax_total ?? 0);

        $payload = [
            'amount' => $amountInCents,
            'title' => 'Pago Factura #' . $invoice->increment_id,
            'currency' => 'COP',
            'phone' => $this->formatPhone($customer->phone_number),
            'email' => $customer->email_address ?? $customer->email ?? null,
            'reference' => (string) $invoice->increment_id,
            'tax' => $taxInCents,
            'external_id' => (string) $invoice->increment_id,
            'description' => 'Cobro de factura en ISPGo',
            'allows' => [
                'cards' => true,
                'accounts' => true,
                'card_extra' => true,
                'realtime' => true,
                'pse' => true,
                'transfiya' => true
            ],
        ];


        return $payload;
    }

    protected function buildCustomerPayload(Customer $customer): array
    {
        return [
            'user_type' => 'natural',
            'first_name' => $customer->first_name,
            'last_name' => $customer->last_name,
            'email' => $customer->email_address ?? $customer->email ?? null,
            'phone' => $this->formatPhone($customer->phone_number),
            'document_type' => $customer->document_type ?? 'CC',
            'document_number' => (string) ($customer->identity_document ?? $customer->document_number ?? ''),
            'enable_notifications' => true,
            'nationality' => 'CO',
            'birthdate' => optional($customer->date_of_birth)->format('Y-m-d'),
        ];
    }

    protected function idempotencyKey(string $scope, string $id): string
    {
        return $scope . '_' . $id . '_' . date('Ymd') . '_' . Str::random(8);
    }

    protected function formatPhone(?string $phone): ?string
    {
        if (!$phone) return null;
        $phone = preg_replace('/\D+/', '', $phone);
        if (Str::startsWith($phone, '57')) {
            return '+' . $phone;
        }
        return '+57' . $phone;
    }

    protected function extractErrorMessage(\Illuminate\Http\Client\Response $response): string
    {
        $body = $response->json();
        if (is_array($body)) {
            return $body['message'] ?? $body['error'] ?? 'Error desconocido';
        }
        return $response->body() ?: 'Error desconocido';
    }
}
