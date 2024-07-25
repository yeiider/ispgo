<?php

namespace App\Listeners;

use App\Events\InvoicePaid;
use App\Mail\DynamicEmail;
use App\Models\EmailTemplate;
use App\Models\Invoice\Invoice;
use App\Settings\InvoiceProviderConfig;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use App\Exceptions\EmailTemplateNotFoundException;

class AfterPayingInvoice
{
    use InteractsWithQueue;

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
        if (InvoiceProviderConfig::enableServiceWhenPaying()) {
            $invoice->service->activate();
        }
    }
}
