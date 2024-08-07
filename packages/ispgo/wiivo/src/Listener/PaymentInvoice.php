<?php

namespace Ispgo\Wiivo\Listener;

use App\Events\InvoicePaid;
use App\Models\Invoice\Invoice;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Queue\InteractsWithQueue;
use Ispgo\Wiivo\ServiceWiivo;
use Ispgo\Wiivo\WiivoConfigProvider;
use Illuminate\Support\Facades\Log;
use Laravel\Nova\Notifications\NovaNotification;

class PaymentInvoice implements ShouldQueue
{
    use InteractsWithQueue;


    /**
     * The name of the queue the job should be sent to.
     *
     * @var string|null
     */
    public $queue = 'invoice_notifications';

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 3;

    /**
     * The number of seconds the job can run before timing out.
     *
     * @var int
     */
    public $timeout = 120;

    /**
     * The number of seconds to delay the job.
     *
     * @var int
     */
    public $delay = 10;

    /**
     * Handle the event.
     *
     * @param InvoicePaid $event
     * @return void
     */
    public function handle(InvoicePaid $event)
    {
        try {
            if (WiivoConfigProvider::getNotifyPaymentTemplate()) {
                $invoice = $event->invoice;
                $payload = $this->preparePayload($invoice);
                $wiivoService = new ServiceWiivo();
                $wiivoService->sendMessage($payload);
            }
        } catch (ConnectionException $e) {
            $this->notifyError($event->invoice->id, $e->getMessage());
            Log::error("Failed to send payment notification for invoice ID {$event->invoice->id}: " . $e->getMessage());
        } catch (\Exception $e) {
            $this->notifyError($event->invoice->id, $e->getMessage());
            Log::error("An error occurred while handling InvoicePaid event for invoice ID {$event->invoice->id}: " . $e->getMessage());
        }
    }

    /**
     * Prepare the payload for notification.
     *
     * @param Invoice $invoice
     * @return array
     */
    private function preparePayload(Invoice $invoice): array
    {
        $customerName = $invoice->full_name;
        $phonePrefix = WiivoConfigProvider::getTelephonePrefix();
        $phone = $phonePrefix.$invoice->customer->phone_number;

        $messageTemplate = WiivoConfigProvider::getNotifyPaymentTemplate();
        $message = str_replace('{name}', $customerName, $messageTemplate);
        return ["message" => $message, 'phone' => $phone,];
    }

    /**
     * Notify about an error.
     *
     * @param int $invoiceId
     * @param string $errorMessage
     * @return void
     */
    private function notifyError(int $invoiceId, string $errorMessage): void
    {
        $admin = \App\Models\User::role('super-admin')->first();

        $notification = NovaNotification::make()
            ->message("Ocurrió un error al enviar la notificación de pago para la factura ID: {$invoiceId}. Error: {$errorMessage}")
            ->type('error')
            ->icon('exclamation-circle');

        $admin->notify($notification);
    }
}
