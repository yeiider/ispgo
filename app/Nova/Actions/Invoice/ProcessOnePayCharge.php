<?php

namespace App\Nova\Actions\Invoice;

use App\Models\Invoice\Invoice;
use App\Settings\OnePaySettings;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http; // kept for backward compatibility, not used now
use App\Services\Payments\OnePay\OnePayHandler;
use Illuminate\Support\Facades\Log;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Heading;
use Laravel\Nova\Http\Requests\NovaRequest;

class ProcessOnePayCharge extends Action implements ShouldQueue
{
    use InteractsWithQueue, Queueable;

    public $name = 'Procesar Cobro OnePay';

    public function __construct()
    {
        // Configurar propiedades de cola en el constructor
        $this->connection = 'redis';
        $this->queue = 'redis';
    }

    public function name()
    {
        return $this->name;
    }

    public function fields(NovaRequest $request)
    {
        return [
            Heading::make('Esta acción creará o reenviará un cobro en OnePay.'),
        ];
    }

    /**
     * Handle the action.
     * IMPORTANTE: No usar closures o funciones anónimas en este método
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        // Validación temprana de configuración
        if (!$this->validateOnePayConfiguration()) {
            return Action::danger('OnePay no está configurado correctamente. Verifique la configuración.');
        }

        $baseUrl = rtrim(OnePaySettings::baseUrl(), '/');
        $token = OnePaySettings::apiToken();

        $processedCount = 0;
        $errors = [];

        // EVITAR usar Collection methods que usen closures como map(), filter(), etc.
        // Usar foreach tradicional en su lugar
        foreach ($models as $invoice) {
            /** @var Invoice $invoice */
            try {
                $this->processInvoice($invoice, $baseUrl, $token);
                $processedCount++;
            } catch (\Throwable $e) {
                $errors[] = "Factura #{$invoice->increment_id}: {$e->getMessage()}";
                Log::error('OnePay action error', [
                    'invoice_id' => $invoice->id,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        }

        return $this->buildResponse($processedCount, $errors, count($models));
    }

    protected function validateOnePayConfiguration(): bool
    {
        if (!OnePaySettings::enabled()) {
            Log::warning('OnePay not enabled in configuration');
            return false;
        }

        $baseUrl = OnePaySettings::baseUrl();
        $token = OnePaySettings::apiToken();

        if (!$baseUrl || !$token) {
            Log::error('Missing OnePay configuration', [
                'has_base_url' => !empty($baseUrl),
                'has_token' => !empty($token)
            ]);
            return false;
        }

        return true;
    }

    protected function processInvoice(Invoice $invoice, string $baseUrl, string $token): void
    {
        // Centralize logic through OnePayHandler
        $handler = new OnePayHandler();
        if (!$invoice->onepay_charge_id) {
            $data = $handler->createPayment($invoice);
            $this->updateInvoiceWithChargeData($invoice, $data);
        } else {
            $handler->resendPayment($invoice);
        }
    }

    protected function createCharge(Invoice $invoice, string $baseUrl, string $token): void
    {
        $endpoint = $baseUrl . '/payments';
        $payload = $this->buildCreatePayload($invoice);

        Log::info('OnePay creating charge', [
            'invoice_id' => $invoice->id,
            'payload' => $payload
        ]);

        $response = Http::timeout(30)
            ->withToken($token)
            ->acceptJson()
            ->asJson()
            ->withHeaders([
                'x-idempotency' => $this->generateIdempotencyKey($invoice, 'create')
            ])
            ->post($endpoint, $payload);

        if (!$response->successful()) {
            $errorMessage = $this->extractErrorMessage($response);
            Log::warning('OnePay create failed', [
                'invoice_id' => $invoice->id,
                'status' => $response->status(),
                'error' => $errorMessage
            ]);
            throw new \Exception("Error al crear cobro OnePay: {$errorMessage}");
        }

        $data = $response->json();
        $this->updateInvoiceWithChargeData($invoice, $data);

        Log::info('OnePay charge created successfully', [
            'invoice_id' => $invoice->id,
            'charge_id' => $data['id'] ?? null
        ]);
    }

    protected function resendCharge(Invoice $invoice, string $baseUrl, string $token): void
    {
        $endpoint = $baseUrl . '/payments/' . $invoice->onepay_charge_id;

        Log::info('OnePay resending charge', [
            'invoice_id' => $invoice->id,
            'charge_id' => $invoice->onepay_charge_id
        ]);

        $response = Http::timeout(30)
            ->withToken($token)
            ->acceptJson()
            ->withHeaders([
                'x-idempotency' => $this->generateIdempotencyKey($invoice, 'resend')
            ])
            ->post($endpoint);

        if (!$response->successful() && $response->status() !== 204) {
            $errorMessage = $this->extractErrorMessage($response);
            Log::warning('OnePay resend failed', [
                'invoice_id' => $invoice->id,
                'charge_id' => $invoice->onepay_charge_id,
                'status' => $response->status(),
                'error' => $errorMessage
            ]);
            throw new \Exception("Error al recordar cobro OnePay: {$errorMessage}");
        }

        Log::info('OnePay charge resent successfully', [
            'invoice_id' => $invoice->id,
            'charge_id' => $invoice->onepay_charge_id
        ]);
    }

    protected function buildCreatePayload(Invoice $invoice): array
    {
        // Validar que la factura tenga los datos necesarios
        if (!$invoice->total || $invoice->total <= 0) {
            throw new \Exception("La factura #{$invoice->increment_id} no tiene un monto válido");
        }

        if (!$invoice->customer) {
            throw new \Exception("La factura #{$invoice->increment_id} no tiene cliente asociado");
        }

        // OnePay expects amounts in cents
        $amountInCents = (int) $invoice->total;
        $taxInCents = (int) ($invoice->tax_total ?? 0);
        $customer = $invoice->customer;

        return [
            'amount' => $amountInCents,
            'title' => 'Pago Factura #' . $invoice->increment_id,
            'currency' => 'COP',
            'phone' => '+57'.$customer->phone_number,
            'email' => $customer->email,
            'reference' => (string) $invoice->increment_id,
            'tax' => $taxInCents,
            'external_id' =>  $invoice->increment_id,
            'description' => 'Cobro de factura en ISPGo',
        ];
    }

    protected function updateInvoiceWithChargeData(Invoice $invoice, array $data): void
    {
        $invoice->update([
            'onepay_charge_id' => $data['id'] ?? null,
            'onepay_payment_link' => $data['payment_link'] ?? null,
            'onepay_status' => $data['status'] ?? 'pending',
            'onepay_metadata' => $data,
        ]);
    }

    protected function generateIdempotencyKey(Invoice $invoice, string $action): string
    {
        return sprintf('%s_%d_%s_%d', $action, $invoice->id, date('Y-m-d'), time());
    }

    protected function extractErrorMessage(\Illuminate\Http\Client\Response $response): string
    {
        $body = $response->json();

        if (is_array($body)) {
            return $body['message'] ?? $body['error'] ?? 'Error desconocido';
        }

        return $response->body() ?: 'Error desconocido';
    }

    protected function buildResponse(int $processedCount, array $errors, int $totalCount): mixed
    {
        if (empty($errors)) {
            return Action::message("Se procesaron exitosamente {$processedCount} facturas en OnePay.");
        }

        if ($processedCount === 0) {
            $errorSummary = implode('; ', $this->limitErrors($errors, 3));
            return Action::danger("No se pudo procesar ninguna factura: {$errorSummary}");
        }

        $errorCount = count($errors);
        $errorSummary = implode('; ', $this->limitErrors($errors, 2));

        return Action::message(
            "Se procesaron {$processedCount} de {$totalCount} facturas. " .
            "{$errorCount} errores: {$errorSummary}" .
            ($errorCount > 2 ? '...' : '')
        );
    }

    /**
     * Helper method para limitar errores sin usar closures
     */
    protected function limitErrors(array $errors, int $limit): array
    {
        $result = [];
        $count = 0;

        foreach ($errors as $error) {
            if ($count >= $limit) {
                break;
            }
            $result[] = $error;
            $count++;
        }

        return $result;
    }
}
