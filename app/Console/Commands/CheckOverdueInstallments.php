<?php

namespace App\Console\Commands;

use App\Events\InstallmentOverdue;
use App\Models\Credit\CreditInstallment;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CheckOverdueInstallments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'credits:check-overdues';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for installments that are due today and mark them as overdue';

    /**
     * Execute the console command.
     *
     * @return
     */
    public function handle()
    {
        $this->info('Checking for overdue installments...');

        $results = [
            'checked' => 0,
            'marked_overdue' => 0,
            'errors' => 0,
        ];

        // Get all pending installments that are past their due date
        $overdueInstallments = CreditInstallment::where('status', 'pending')
            ->where('due_date', '<', Carbon::today())
            ->with('creditAccount')
            ->get();

        $this->info("Found {$overdueInstallments->count()} potentially overdue installments");

        foreach ($overdueInstallments as $installment) {
            try {
                $results['checked']++;

                // Skip installments for accounts in grace period
                if ($installment->creditAccount->status === 'in_grace') {
                    $this->line("Skipping installment #{$installment->id} - account in grace period");
                    continue;
                }

                // Mark as overdue
                $installment->status = 'overdue';
                $installment->save();

                // Dispatch event
                event(new InstallmentOverdue($installment));

                $results['marked_overdue']++;
                $this->line("Marked installment #{$installment->id} as overdue");

            } catch (\Exception $e) {
                $results['errors']++;
                $this->error("Error processing installment #{$installment->id}: {$e->getMessage()}");
                Log::error("Error marking installment #{$installment->id} as overdue: " . $e->getMessage());
            }
        }

        $this->info("Processed: {$results['checked']} | Marked overdue: {$results['marked_overdue']} | Errors: {$results['errors']}");
    }
}
