<?php

namespace App\Nova\Actions\Invoice;

use App\Models\Invoice\Invoice;
use App\Settings\OnePaySettings;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http; // deprecated here
use App\Services\Payments\OnePay\OnePayHandler;
use Illuminate\Support\Facades\Log;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Heading;
use Laravel\Nova\Http\Requests\NovaRequest;

class DeleteOnePayCharge extends Action implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    public $name = 'Eliminar Cobro OnePay';

    public function __construct()
    {
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
            Heading::make('Esta acción eliminará/anulará el cobro en OnePay si está pendiente.'),
        ];
    }

    public function handle(ActionFields $fields, Collection $models)
    {
        if (!OnePaySettings::enabled()) {
            return Action::danger('OnePay no está habilitado en la configuración.');
        }

        $baseUrl = rtrim(OnePaySettings::baseUrl() ?? '', '/');
        $token = OnePaySettings::apiToken();

        if (!$baseUrl || !$token) {
            return Action::danger('Falta configurar onepay_base_url o onepay_api_token.');
        }

        $processedCount = 0;
        $errors = [];
        $handler = new OnePayHandler();

        foreach ($models as $invoice) {
            /** @var Invoice $invoice */
            if (!$invoice->onepay_charge_id) {
                $errors[] = "Factura #{$invoice->increment_id}: No tiene cobro OnePay asociado";
                continue;
            }

            try {
                $endpoint = $baseUrl . '/payments/' . $invoice->onepay_charge_id;
                $response = Http::timeout(30)
                    ->withToken($token)
                    ->delete($endpoint);

                if (!$response->successful() && $response->status() !== 204) {
                    Log::warning('OnePay delete failed', [
                        'invoice_id' => $invoice->id,
                        'charge_id' => $invoice->onepay_charge_id,
                        'status' => $response->status(),
                        'body' => $response->body()
                    ]);
                    $errors[] = "Factura #{$invoice->increment_id}: Error al eliminar cobro OnePay";
                    continue;
                }

                // Clear local fields
                $invoice->update([
                    'onepay_charge_id' => null,
                    'onepay_payment_link' => null,
                    'onepay_status' => null,
                    'onepay_metadata' => null,
                ]);

                $processedCount++;

                Log::info('OnePay charge deleted successfully', [
                    'invoice_id' => $invoice->id,
                    'former_charge_id' => $invoice->onepay_charge_id
                ]);

            } catch (\Throwable $e) {
                Log::error('OnePay delete action error', [
                    'invoice_id' => $invoice->id,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                $errors[] = "Factura #{$invoice->increment_id}: {$e->getMessage()}";
            }
        }

        return $this->buildResponse($processedCount, $errors, count($models));
    }

    protected function buildResponse(int $processedCount, array $errors, int $totalCount): mixed
    {
        if (empty($errors)) {
            return Action::message("Se eliminaron exitosamente {$processedCount} cobros OnePay.");
        }

        if ($processedCount === 0) {
            $errorSummary = implode('; ', $this->limitErrors($errors, 3));
            return Action::danger("No se pudo eliminar ningún cobro: {$errorSummary}");
        }

        $errorCount = count($errors);
        $errorSummary = implode('; ', $this->limitErrors($errors, 2));

        return Action::message(
            "Se eliminaron {$processedCount} de {$totalCount} cobros. " .
            "{$errorCount} errores: {$errorSummary}" .
            ($errorCount > 2 ? '...' : '')
        );
    }

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
