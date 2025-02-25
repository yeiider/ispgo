<?php

namespace App\Console\Commands;

use App\Mail\DynamicEmail;
use App\Mail\InvoiceEmail;
use App\Models\EmailTemplate;
use App\Models\Invoice\Invoice;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;


class SendInvoiceEmail extends Command
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-invoice-email {increment_id} {template_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send an email with the invoice corresponding to increment_id and taking the template_id of the invoice';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $incrementId = $this->argument('increment_id');
        $templateId = $this->argument('template_id');

        $invoice = Invoice::where('increment_id', $incrementId)
            ->with('customer')
            ->first();

        $emailTemplate = EmailTemplate::where('id', $templateId)->first();

        $this->info('Buscando factura con increment_id ' . $incrementId . ' ' . $templateId);

        if (!$invoice) {
            $this->error('An invoice with the increment_id provided was not found.');
            return;
        }

        if (!$emailTemplate) {
            $this->error('An email template with the template_id provided was not found.');
            return;
        }

        $recipient = $invoice->customer->email_address;

        if (!$recipient) {
            $this->error('The invoice does not have an associated email.');
            return;
        }

        // Mail::to($recipient)->send(new InvoiceEmail($invoice));
        $img_header = asset('/img/invoice/email-header.jpeg');
        Mail::to($recipient)->send(new DynamicEmail(['invoice' => $invoice], $emailTemplate, $img_header));

        $this->info('Email successfully sent to ' . $recipient);

    }
}
