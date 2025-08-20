<?php

namespace App\Jobs;

use App\Helpers\Utils;
use App\Models\EmailTemplate;
use App\Models\Invoice\Invoice;
use App\Settings\InvoiceProviderConfig;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendInvoicesNotificationBatch implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public ?string $startDate;
    public ?string $endDate;
    public string $status;

    /**
     * Create a new job instance.
     */
    public function __construct(?string $startDate = null, ?string $endDate = null, string $status = 'all')
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->status = $status;

    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $templateId = InvoiceProviderConfig::emailTemplateCreatedInvoice();
        if (!$templateId) {
            Log::warning('SendInvoicesNotificationBatch: email template not configured.');
            return;
        }

        $emailTemplate = EmailTemplate::find($templateId);
        if (!$emailTemplate) {
            Log::warning('SendInvoicesNotificationBatch: email template not found. ID=' . $templateId);
            return;
        }

        $statuses = $this->resolveStatuses($this->status);

        $query = Invoice::query();

        if ($this->startDate) {
            $query->whereDate('issue_date', '>=', $this->startDate);
        }
        if ($this->endDate) {
            $query->whereDate('issue_date', '<=', $this->endDate);
        }

        if (!empty($statuses)) {
            $query->whereIn('status', $statuses);
        }

        // Only invoices with a valid recipient and issued state
        $query->whereHas('customer', function ($c) {
            $c->whereNotNull('email_address')->where('email_address', '!=', '');
        });
        $query->where(function ($q) {
            $q->whereNull('state')->orWhere('state', 'issued');
        })->orderBy('id');

        $img_header = asset('/img/invoice/email-header.jpeg');

        $sent = 0; $errors = 0; $processed = 0;

        $query->chunk(200, function ($invoices) use (&$sent, &$errors, &$processed, $emailTemplate, $img_header) {
            foreach ($invoices as $invoice) {
                $processed++;
                try {
                    Utils::sendInvoiceEmail($invoice, $emailTemplate, $img_header);
                    $sent++;
                } catch (\Throwable $e) {
                    $errors++;
                    Log::error('Error sending invoice email', [
                        'invoice_id' => $invoice->id,
                        'increment_id' => $invoice->increment_id,
                        'error' => $e->getMessage(),
                    ]);
                }
            }
        });

        Log::info('SendInvoicesNotificationBatch finished', [
            'status_filter' => $this->status,
            'date_from' => $this->startDate,
            'date_to' => $this->endDate,
            'processed' => $processed,
            'sent' => $sent,
            'errors' => $errors,
        ]);
    }

    private function resolveStatuses(string $status): array
    {
        $s = strtolower($status);
        return match ($s) {
            'paid' => ['paid'],
            'unpaid' => ['unpaid'],
            'overdue' => ['overdue'],
            'canceled', 'cancelled' => ['canceled'],
            'all', '' => ['paid', 'unpaid', 'overdue', 'canceled'],
            default => [$s],
        };
    }
}
