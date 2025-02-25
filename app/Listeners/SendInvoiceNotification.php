<?php

namespace App\Listeners;

use App\Events\InvoiceCreated;
use App\Helpers\Utils;
use App\Models\EmailTemplate;
use App\Models\Invoice\Invoice;
use App\Settings\InvoiceProviderConfig;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendInvoiceNotification implements ShouldQueue
{
    use InteractsWithQueue;


    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param InvoiceCreated $event
     * @return void
     */
    public function handle(InvoiceCreated $event): void
    {
        /**
         * @var Invoice $invoice;
         */
        $invoice = $event->invoice;

        $templateId = InvoiceProviderConfig::emailTemplateCreatedInvoice();
        $emailTemplate = EmailTemplate::where('id', $templateId)->first();
        $img_header = asset('/img/invoice/email-header.jpeg');
        Utils::sendInvoiceEmail($invoice, $emailTemplate, $img_header);
    }
}
