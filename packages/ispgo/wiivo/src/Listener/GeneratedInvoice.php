<?php

namespace Ispgo\Wiivo\Listener;

use App\Events\InvoiceIssued;
use App\Models\Invoice\Invoice;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Queue\InteractsWithQueue;
use Ispgo\Wiivo\ServiceWiivo;
use Ispgo\Wiivo\WiivoConfigProvider;
use Illuminate\Support\Facades\Log;
use Laravel\Nova\Notifications\NovaNotification;
use App\PaymentMethods\Wompi;

class GeneratedInvoice implements ShouldQueue
{
    use InteractsWithQueue;

    public $queue = 'redis';
    public $tries = 3;
    public $timeout = 120;
    public $delay = 10;

    public function handle(InvoiceIssued $event): void
    {
        try {
            if (WiivoConfigProvider::getNotifyInvoice()) {
                $invoice = $event->invoice;
                $payload = $this->preparePayload($invoice);
                $wiivoService = new ServiceWiivo();
                $wiivoService->sendMessage($payload);
            }
        } catch (ConnectionException $e) {
            Log::error("Failed to send invoice notification for invoice ID {$event->invoice->id}: " . $e->getMessage());
        } catch (\Exception $e) {
            Log::error("An error occurred while handling InvoiceCreated event for invoice ID {$event->invoice->id}: " . $e->getMessage());
        }
    }

    /**
     * @throws \Exception
     */
    private function preparePayload(Invoice $invoice): array
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
            $paymentLink = Wompi::getPaymentLink($invoice);
        }

        $message = str_replace(
            ['{name}', '{due_date}', '{payment_link}', '{reference}','{amount}'],
            [
                $customerName,
                $dueDate,
                $paymentLink ?: 'N/A',
                $reference,
                $amount
            ],
            $messageTemplate
        );

        Log::info($message . $phone);

        return ["message" => $message, 'phone' => $phone];
    }

    private function notifyError(int $invoiceId, string $errorMessage): void
    {
        $admin = \App\Models\User::where('role', 'super-admin')->first();

        if ($admin) {
            $notification = NovaNotification::make()
                ->message("Ocurrió un error al enviar la notificación de la factura ID: {$invoiceId}. Error: {$errorMessage}")
                ->type('error')
                ->icon('exclamation-circle');

            $admin->notify($notification);
        } else {
            Log::error("Admin user with role 'super-admin' not found.");
        }
    }
}
