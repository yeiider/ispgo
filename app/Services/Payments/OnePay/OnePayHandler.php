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
        return $this->createInvoice($invoice);
    }

    public function createInvoice(Invoice $invoice): array
    {
        $payload = $this->buildInvoicePayload($invoice);
        $endpoint = $this->baseUrl . '/invoices';

        Log::info('OnePay creating invoice', [
            'invoice_id' => $invoice->id,
            'payload' => $payload,
        ]);

        $response = Http::timeout(30)
            ->withToken($this->token)
            ->acceptJson()
            ->asJson()
            ->withHeaders([
                'x-idempotency' => $this->idempotencyKey('invoice_create', (string)$invoice->id),
            ])
            ->post($endpoint, $payload);

        if (!$response->successful()) {
            $msg = $this->extractErrorMessage($response);
            Log::warning('OnePay create invoice failed', [
                'invoice_id' => $invoice->id,
                'status' => $response->status(),
                'error' => $msg,
            ]);
            throw new \Exception("Error al crear factura OnePay: {$msg}");
        }

        return (array) $response->json();
    }

    public function getInvoice(string $invoiceId): array
    {
        $endpoint = $this->baseUrl . '/invoices/' . $invoiceId;
        $response = Http::timeout(30)
            ->withToken($this->token)
            ->acceptJson()
            ->get($endpoint);

        if (!$response->successful()) {
            $msg = $this->extractErrorMessage($response);
            throw new \Exception("Error al consultar factura OnePay: {$msg}");
        }

        return (array) $response->json();
    }

    public function updateInvoice(string $invoiceId, array $data): array
    {
        $endpoint = $this->baseUrl . '/invoices/' . $invoiceId;
        $response = Http::timeout(30)
            ->withToken($this->token)
            ->acceptJson()
            ->asJson()
            ->patch($endpoint, $data);

        if (!$response->successful()) {
            $msg = $this->extractErrorMessage($response);
            throw new \Exception("Error al actualizar factura OnePay: {$msg}");
        }

        return (array) $response->json();
    }

    /**
     * Elimina la factura en OnePay.
     *
     * @param string $invoiceId  ID de la factura (/invoices) en OnePay.
     * @param string $reason     DELETE_FROM_PROVIDER | PAID_FROM_PROVIDER.
     */
    public function deleteInvoice(string $invoiceId, string $reason = 'DELETE_FROM_PROVIDER'): void
    {
        $endpoint = $this->baseUrl . '/invoices/' . $invoiceId;
        $response = Http::timeout(30)
            ->withToken($this->token)
            ->acceptJson()
            ->asJson()
            ->delete($endpoint, [
                'reason' => $reason,
            ]);

        if (!$response->successful() && $response->status() !== 204) {
            $msg = $this->extractErrorMessage($response);
            throw new \Exception("Error al eliminar factura OnePay: {$msg}");
        }
    }

    /**
     * Busca el id de la factura (/invoices) en OnePay por su `reference`
     * (= increment_id de la factura en ISPGo).
     */
    public function findInvoiceIdByReference(string $reference): ?string
    {
        $response = Http::timeout(30)
            ->withToken($this->token)
            ->acceptJson()
            ->get($this->baseUrl . '/invoices', [
                'filter[reference]' => $reference,
            ]);

        if (!$response->successful()) {
            $msg = $this->extractErrorMessage($response);
            throw new \Exception("Error consultando factura OnePay por reference: {$msg}");
        }

        foreach (($response->json('data') ?? []) as $row) {
            if (!empty($row['id'])) {
                return $row['id'];
            }
        }

        return null;
    }

    /**
     * Limpia en OnePay la factura/cobro de una factura de ISPGo que se pagó
     * por un medio distinto a OnePay (efectivo, transferencia, Nequi, PSE...).
     *
     * Elimina la FACTURA de OnePay —que es la que genera los recordatorios— y
     * con ella su cobro asociado. Si no se puede resolver el id de la factura,
     * cae al borrado del cobro directo.
     *
     * @return bool true si la factura de OnePay quedó eliminada,
     *              false si solo se pudo cancelar el cobro (o nada).
     */
    public function deleteInvoiceForExternalPayment(Invoice $invoice): bool
    {
        $invoiceId = $invoice->onepay_invoice_id;

        if (!$invoiceId && $invoice->increment_id) {
            try {
                $invoiceId = $this->findInvoiceIdByReference((string) $invoice->increment_id);
            } catch (\Throwable $e) {
                Log::error('Error buscando factura OnePay por reference: ' . $e->getMessage(), [
                    'invoice_id' => $invoice->id,
                    'increment_id' => $invoice->increment_id,
                ]);
            }
        }

        if ($invoiceId) {
            $this->deleteInvoice($invoiceId, 'DELETE_FROM_PROVIDER');
            return true;
        }

        // Fallback: al menos cancelar el cobro directo.
        if ($invoice->onepay_charge_id) {
            $this->deletePayment($invoice->onepay_charge_id);
        }

        return false;
    }

    public function buildInvoicePayload(Invoice $invoice): array
    {
        if (!$invoice->total || $invoice->total <= 0) {
            throw new \Exception("La factura #{$invoice->increment_id} no tiene un monto válido");
        }
        if (!$invoice->customer) {
            throw new \Exception("La factura #{$invoice->increment_id} no tiene cliente asociado");
        }

        $customer = $invoice->customer;
        $amount = (int) $invoice->total;

        return [
            'reference' => (string) $invoice->increment_id,
            'provider_id' => (string) ($customer->identity_document ?? $customer->document_number ?? $customer->id),
            'provider' => \App\Settings\OnePaySettings::provider(),
            'amount' => $amount,
            'name' => 'Pago Factura #' . $invoice->increment_id,
            'description' => 'Cobro de factura en ISPGo',
            'phone' => $this->formatPhone($customer->phone_number),
            'email' => $customer->email_address ?? $customer->email ?? null,
            'due_date' => $invoice->due_date ? \Carbon\Carbon::parse($invoice->due_date)->format('Y-m-d') : null,
            'document_url' => $invoice->url_preview,
            'metadata' => [
                'invoice_id' => (string) $invoice->id,
                'customer_id' => (string) $customer->id,
            ],
        ];
    }

    public function createInvoicesParallel(array $invoices): array
    {
        $responses = Http::pool(function (\Illuminate\Http\Client\Pool $pool) use ($invoices) {
            foreach ($invoices as $invoice) {
                try {
                    $payload = $this->buildInvoicePayload($invoice);
                    $endpoint = $this->baseUrl . '/invoices';

                    $pool->as((string) $invoice->id)->timeout(30)
                        ->withToken($this->token)
                        ->acceptJson()
                        ->asJson()
                        ->withHeaders([
                            'x-idempotency' => $this->idempotencyKey('invoice_create', (string)$invoice->id),
                        ])
                        ->post($endpoint, $payload);
                } catch (\Throwable $e) {
                    Log::error('Error building payload for parallel OnePay invoice', [
                        'invoice_id' => $invoice->id,
                        'error' => $e->getMessage(),
                    ]);
                }
            }
        });

        $results = [];
        foreach ($invoices as $invoice) {
            $response = $responses[$invoice->id] ?? null;
            if ($response && $response->successful()) {
                $results[$invoice->id] = [
                    'success' => true,
                    'data' => $response->json(),
                ];
            } else {
                $errorMsg = $response ? $this->extractErrorMessage($response) : 'No response from OnePay';
                $results[$invoice->id] = [
                    'success' => false,
                    'error' => $errorMsg,
                ];
            }
        }

        return $results;
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
                'card_extra' => false,
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

    public function getPayment(string $paymentId): array
    {
        $endpoint = $this->baseUrl . '/payments/' . $paymentId;
        $response = Http::timeout(30)
            ->withToken($this->token)
            ->acceptJson()
            ->get($endpoint);

        if (!$response->successful()) {
            $msg = $this->extractErrorMessage($response);
            throw new \Exception("Error al consultar cobro OnePay: {$msg}");
        }

        return $response->json();
    }

    public function getPaymentIntents(string $paymentId): array
    {
        $endpoint = $this->baseUrl . '/payments/' . $paymentId . '/intents';
        $response = Http::timeout(30)
            ->withToken($this->token)
            ->acceptJson()
            ->get($endpoint);

        if (!$response->successful()) {
            $msg = $this->extractErrorMessage($response);
            throw new \Exception("Error al consultar intentos de cobro OnePay: {$msg}");
        }

        return (array) $response->json();
    }

    public function getCustomerByDocument(string $documentNumber): ?array
    {
        $endpoint = $this->baseUrl . '/customers';
        $response = Http::timeout(30)
            ->withToken($this->token)
            ->acceptJson()
            ->get($endpoint, [
                'document_number' => $documentNumber,
                'limit' => 20,
                'page' => 1
            ]);

        if (!$response->successful()) {
            $msg = $this->extractErrorMessage($response);
            throw new \Exception("Error al consultar cliente OnePay: {$msg}");
        }

        $data = $response->json('data');
        return !empty($data) ? $data[0] : null;
    }
}
