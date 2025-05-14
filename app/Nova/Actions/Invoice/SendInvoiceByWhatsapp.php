<?php

namespace App\Nova\Actions\Invoice;

use App\Models\Invoice\Invoice;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Actions\ActionResponse;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Field;
use Laravel\Nova\Http\Requests\NovaRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Ispgo\Wiivo\ServiceWiivo;
use Ispgo\Wiivo\WiivoConfigProvider;

class SendInvoiceByWhatsapp extends Action implements ShouldQueue
{
    use InteractsWithQueue, Queueable;

    public function __construct()
    {
        $this->onQueue('redis');
    }

    /**
     * Define un nombre amigable para la acción.
     *
     * @return string
     */
    public function name(): string
    {
        return __('invoice.actions.send_by_whatsapp');
    }


    /**
     * Perform the action on the given models.
     *
     * @param ActionFields $fields
     * @param Collection $models
     * @return ActionResponse
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        foreach ($models as $invoice) {
            try {
                // Validar que se tenga todo lo necesario
                if (!WiivoConfigProvider::getNotifyInvoice()) {
                    throw new \Exception("La configuración de notificaciones no está habilitada.");
                }

                // Preparar el payload para enviar
                $payload = $this->preparePayload($invoice);

                // Usar el servicio Wiivo para enviar el mensaje
                $wiivoService = new ServiceWiivo();
                $wiivoService->sendMessage($payload);

                // Log de éxito
                Log::info("Factura ID {$invoice->id} enviada por WhatsApp al cliente.");
            } catch (\Exception $e) {
                // Manejo de errores
                Log::error(
                    "Error al enviar factura ID {$invoice->id} por WhatsApp: " . $e->getMessage()
                );

                return Action::danger("No se pudo enviar la factura ID {$invoice->id}. Error: {$e->getMessage()}");
            }
        }

        return Action::message("Las facturas se enviaron commentator por WhatsApp.");
    }

    /**
     * Obtener los campos disponibles en la acción.
     *
     * @param NovaRequest $request
     * @return array<int, Field>
     */
    public function fields(NovaRequest $request): array
    {
        return [];
    }

    /**
     * Preparar el payload para enviar la factura como mensaje de WhatsApp.
     *
     * @param Invoice $invoice
     * @return array
     * @throws \Exception
     */
    private function preparePayload($invoice): array
    {
        $customerName = $invoice->full_name;
        $phonePrefix = WiivoConfigProvider::getTelephonePrefix();
        $phone = $phonePrefix . $invoice->customer->phone_number;
        $dueDate = $invoice->due_date->format('Y-m-d');
        $reference = $invoice->increment_id;
        $amount = number_format($invoice->total, 2, ',', '.');

        $messageTemplate = WiivoConfigProvider::getNotifyInvoiceTemplate();

        // Verificar si la plantilla contiene `{payment_link}`
        $paymentLink = null;
        if (str_contains($messageTemplate, '{payment_link}')) {
            $paymentLink = \App\PaymentMethods\Wompi::getPaymentLink($invoice);
        }

        $message = str_replace(
            ['{name}', '{due_date}', '{payment_link}', '{reference}', '{amount}'],
            [
                $customerName,
                $dueDate,
                $paymentLink ?: 'N/A',
                $reference,
                $amount
            ],
            $messageTemplate
        );

        return ["message" => $message, 'phone' => $phone];
    }
}
