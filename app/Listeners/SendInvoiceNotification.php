<?php

namespace App\Listeners;

use App\Events\InvoiceIssued;
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


    /**
     * Handle the event.
     *
     * @param InvoiceIssued $event
     * @return void
     */
    public function handle(InvoiceIssued $event): void
    {
        /**
         * @var Invoice $invoice;
         */
        $invoice = $event->invoice;

        $templateId = InvoiceProviderConfig::emailTemplateCreatedInvoice();
        $emailTemplate = $templateId ? EmailTemplate::where('id', $templateId)->first() : null;
        if (!$emailTemplate) {
            \Log::warning('SendInvoiceNotification: email template not configured or not found. Skipping.', [
                'invoice_id' => $invoice->id,
                'template_id' => $templateId,
            ]);
            return;
        }
        $img_header = asset('/img/invoice/email-header.jpeg');
        Utils::sendInvoiceEmail($invoice, $emailTemplate, $img_header);
    }
}
