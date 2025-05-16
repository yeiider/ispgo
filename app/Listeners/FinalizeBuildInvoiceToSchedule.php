<?php

namespace App\Listeners;

use App\Events\FinalizeInvoice;

use App\Models\Invoice\Invoice;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class FinalizeBuildInvoiceToSchedule implements ShouldQueue
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
     * @return void
     */
    public function handle(): void
    {
        $currentBillingPeriod = now()->format('Y-m'); // Obtener el periodo actual

        $invoices = Invoice::where('billing_period', $currentBillingPeriod)
        ->where('state', 'building')
        ->get();

        foreach ($invoices as $invoice) {
            try {
                $invoice->finalize();
            } catch (\Exception $e) {
                Log::error("Error updating invoice ID {$invoice->id}: " . $e->getMessage()); // Registrar error
            }
        }

    }
}
