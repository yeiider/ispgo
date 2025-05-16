<?php

namespace App\Console\Commands;

use App\Services\Credit\PenaltyService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ApplyPenalties extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'credits:apply-penalties';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Apply penalties to all overdue installments';

    /**
     * The penalty service instance.
     *
     * @var PenaltyService
     */
    protected $penaltyService;

    /**
     * Create a new command instance.
     *
     * @param PenaltyService $penaltyService
     * @return void
     */
    public function __construct(PenaltyService $penaltyService)
    {
        parent::__construct();
        $this->penaltyService = $penaltyService;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Applying penalties to overdue installments...');

        try {
            $results = $this->penaltyService->applyOverduePenalties();

            $this->info("Processed: {$results['processed']} | Penalties applied: {$results['penalties_applied']} | Errors: {$results['errors']}");

            Log::info("Applied penalties to {$results['penalties_applied']} installments");

            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error("Error applying penalties: {$e->getMessage()}");
            Log::error("Error in apply-penalties command: " . $e->getMessage());

            return Command::FAILURE;
        }
    }
}
