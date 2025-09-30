<?php

namespace App\Listeners;

use App\Events\InvoicePaid;
use App\Mail\DynamicEmail;
use App\Models\EmailTemplate;
use App\Models\Invoice\Invoice;
use App\Models\InvoiceItem;
use App\Models\Services\Service;
use App\Settings\InvoiceProviderConfig;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Exceptions\EmailTemplateNotFoundException;

class AfterPayingInvoice implements ShouldQueue
{
    use InteractsWithQueue;
    /**
     * The name of the queue the job should be sent to.
     *
     * @var string|null
     */
    public $queue = 'redis';

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

    private InvoiceProviderConfig $invoiceProviderConfig;



    /**
     * Handle the event.
     *
     * @param InvoicePaid $event
     * @throws EmailTemplateNotFoundException
     */
    public function handle(InvoicePaid $event): void
    {
        $invoice = $event->invoice;
        $this->sendEmail($invoice);
        $this->activateService($invoice);
    }

    /**
     * Send an email after the invoice is paid.
     *
     * @param Invoice $invoice
     * @throws EmailTemplateNotFoundException
     */
    private function sendEmail(Invoice $invoice): void
    {
        if (InvoiceProviderConfig::sendEmailByPaying()) {
            $emailTemplateId = InvoiceProviderConfig::emailTemplatePaying();
            $emailTemplate = EmailTemplate::find($emailTemplateId);

            if (!$emailTemplate) {
                throw new EmailTemplateNotFoundException("Email template with ID $emailTemplateId not found.");
            }

            $toEmail = $invoice->email_address;
            Mail::to($toEmail)->send(new DynamicEmail(['invoice' => $invoice], $emailTemplate));
        }
    }

    private function activateService(Invoice $invoice): void
    {
        if (!InvoiceProviderConfig::enableServiceWhenPaying()) {
            return;
        }

        try {
            // Obtener todos los items de la factura que tengan service_id
            $invoiceItems = $invoice->items()->whereNotNull('service_id')->with('service')->get();

            foreach ($invoiceItems as $item) {
                $service = $item->service;

                // Verificar que el servicio existe
                if (!$service) {
                    continue;
                }

                $customerId = $invoice->customer_id;

                // Verificar si hay otras facturas impagas que contengan items de este servicio para el mismo cliente
                $hasUnpaidInvoices = $this->hasUnpaidInvoicesForService($service, $customerId, $invoice->id);

                if (!$hasUnpaidInvoices) {
                    // Activar el servicio solo si no está activo
                    if ($service->service_status !== 'active') {
                        $service->activate();

                        Log::info("Servicio activado después del pago", [
                            'service_id' => $service->id,
                            'customer_id' => $customerId,
                            'invoice_id' => $invoice->id,
                            'item_id' => $item->id,
                            'previous_status' => $service->getOriginal('service_status'),
                            'new_status' => 'active'
                        ]);
                    }
                } else {
                    Log::info("Servicio no activado: existen facturas impagas", [
                        'service_id' => $service->id,
                        'customer_id' => $customerId,
                        'invoice_id' => $invoice->id,
                        'item_id' => $item->id,
                        'current_status' => $service->service_status
                    ]);
                }
            }
        } catch (\Exception $e) {
            Log::error("Error al activar servicios después del pago", [
                'invoice_id' => $invoice->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    /**
     * Verificar si existen facturas impagas que contengan items del servicio especificado para el cliente
     *
     * @param Service $service
     * @param int $customerId
     * @param int $excludeInvoiceId ID de la factura a excluir (la que se acaba de pagar)
     * @return bool
     */
    private function hasUnpaidInvoicesForService(Service $service, int $customerId, int $excludeInvoiceId): bool
    {
        return InvoiceItem::whereHas('invoice', function ($query) use ($customerId, $excludeInvoiceId) {
                $query->where('customer_id', $customerId)
                      ->where('id', '!=', $excludeInvoiceId)
                      ->where('status', '!=', 'paid'); // Ajusta este estado según tu sistema
            })
            ->where('service_id', $service->id)
            ->exists();
    }
}
