<?php

namespace App\Console\Commands;

use App\Helpers\Utils;
use App\Models\EmailTemplate;
use App\Models\Invoice\Invoice;
use App\Settings\InvoiceProviderConfig;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class SendUnpaidInvoices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'invoices:send-unpaid {--limit=0 : Max number of invoices to process (0 = no limit)} {--status=all : Filter by status: unpaid, overdue, or all} {--dry-run : Lists invoices without sending emails} {--period= : Periodo a filtrar: this-month, last-month, YYYY-MM o YYYY-MM..YYYY-MM}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send invoice emails for invoices that are not paid (unpaid/overdue) using the configured template.';

    public function handle(): int
    {
        $templateId = InvoiceProviderConfig::emailTemplateCreatedInvoice();
        if (!$templateId) {
            $this->error('Email template for created invoice is not configured.');
            return Command::FAILURE;
        }

        $emailTemplate = EmailTemplate::where('id', $templateId)->first();
        if (!$emailTemplate) {
            $this->error("Email template with ID {$templateId} was not found.");
            return Command::FAILURE;
        }

        $statusOpt = strtolower((string)$this->option('status'));
        $statuses = match ($statusOpt) {
            'unpaid' => ['unpaid'],
            'overdue' => ['overdue'],
            default => ['unpaid', 'overdue'],
        };

        $limit = (int)$this->option('limit');
        $dryRun = (bool)$this->option('dry-run');
        $periodOpt = (string)($this->option('period') ?? '');
        $dateRange = $this->parsePeriodOption($periodOpt);

        $query = Invoice::query()
            ->whereIn('status', $statuses)
            ->where(function ($q) {
                // Ensure the invoice has a recipient email available
                $q->whereHas('customer', function ($c) {
                    $c->whereNotNull('email_address')->where('email_address', '!=', '');
                });
            })
            ->where(function ($q) {
                // Prefer sending only issued invoices (avoid building)
                $q->whereNull('state')->orWhere('state', 'issued');
            })
            ->when($dateRange !== null, function ($q) use ($dateRange) {
                [$start, $end] = $dateRange;
                $q->whereBetween('issue_date', [$start, $end]);
            })
            ->orderBy('id');

        $total = $query->count();
        if ($total === 0) {
            $this->info('No matching invoices found to process.');
            return Command::SUCCESS;
        }

        $this->info("Found {$total} invoice(s) with status [" . implode(',', $statuses) . '].');
        if ($limit > 0) {
            $this->info("Limiting to {$limit} invoice(s).");
        }
        if ($dryRun) {
            $this->info('Running in dry-run mode. No emails will be sent.');
        }
        if ($dateRange !== null) {
            [$start, $end] = $dateRange;
            $this->info("Filtering by period: {$start->toDateString()} .. {$end->toDateString()}");
        }

        $processed = 0; $sent = 0; $errors = 0;
        $img_header = asset('/img/invoice/email-header.jpeg');

        // Use chunking for memory safety; still honor limit if provided
        $remaining = $limit > 0 ? $limit : PHP_INT_MAX;
        $query->chunk(200, function ($invoices) use (&$processed, &$sent, &$errors, &$remaining, $dryRun, $emailTemplate, $img_header) {
            foreach ($invoices as $invoice) {
                if ($remaining <= 0) {
                    break; // stop processing further
                }
                $processed++;
                $remaining--;

                $recipient = $invoice->email_address;
                if (!$recipient) {
                    // Should not happen due to query filter, but guard anyway
                    $errors++;
                    Log::warning("Invoice {$invoice->id} skipped: no recipient email.");
                    continue;
                }

                if ($dryRun) {
                    $this->line("[DRY-RUN] Would send invoice {$invoice->increment_id} to {$recipient}");
                    continue;
                }

                try {
                    Utils::sendInvoiceEmail($invoice, $emailTemplate, $img_header);
                    $sent++;
                    $this->line("Sent invoice {$invoice->increment_id} to {$recipient}");
                } catch (\Throwable $e) {
                    $errors++;
                    Log::error("Error sending invoice {$invoice->id}: " . $e->getMessage(), ['invoice_id' => $invoice->id]);
                    $this->error("Error sending invoice {$invoice->increment_id}: {$e->getMessage()}");
                }
            }
        });

        $this->info("Processed: {$processed} | Emails sent: {$sent} | Errors: {$errors}");
        return $errors > 0 ? Command::FAILURE : Command::SUCCESS;
    }

    private function parsePeriodOption(?string $opt): ?array
    {
        $opt = trim((string)$opt);
        if ($opt === '') {
            return null;
        }

        $now = Carbon::now();

        if ($opt === 'this-month') {
            $start = $now->copy()->startOfMonth();
            $end = $now->copy()->endOfMonth();
            return [$start, $end];
        }

        if ($opt === 'last-month') {
            $last = $now->copy()->subMonth();
            return [$last->copy()->startOfMonth(), $last->copy()->endOfMonth()];
        }

        // YYYY-MM exact month
        if (preg_match('/^\d{4}-\d{2}$/', $opt)) {
            $start = Carbon::createFromFormat('Y-m', $opt)->startOfMonth();
            $end = Carbon::createFromFormat('Y-m', $opt)->endOfMonth();
            return [$start, $end];
        }

        // YYYY-MM..YYYY-MM range
        if (preg_match('/^(\d{4}-\d{2})\.\.(\d{4}-\d{2})$/', $opt, $m)) {
            $start = Carbon::createFromFormat('Y-m', $m[1])->startOfMonth();
            $end = Carbon::createFromFormat('Y-m', $m[2])->endOfMonth();
            if ($end->lt($start)) {
                [$start, $end] = [$end, $start];
            }
            return [$start, $end];
        }

        // Fallback: intentar YYYY-MM-DD..YYYY-MM-DD
        if (preg_match('/^(\d{4}-\d{2}-\d{2})\.\.(\d{4}-\d{2}-\d{2})$/', $opt, $m)) {
            $start = Carbon::createFromFormat('Y-m-d', $m[1])->startOfDay();
            $end = Carbon::createFromFormat('Y-m-d', $m[2])->endOfDay();
            if ($end->lt($start)) {
                [$start, $end] = [$end, $start];
            }
            return [$start, $end];
        }

        // Si no coincide ningún formato, informar y no filtrar
        $this->warn("Periodo inválido '{$opt}'. Formatos permitidos: this-month, last-month, YYYY-MM o YYYY-MM..YYYY-MM");
        return null;
    }
}
