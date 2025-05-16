<?php

namespace App\Services\Credit;

use App\Models\Credit\AccountEntry;
use App\Models\Credit\CreditAccount;
use App\Models\Credit\CreditInstallment;
use App\Settings\GeneralProviderConfig;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PenaltyService
{
    /**
     * Apply penalties to all overdue installments.
     *
     * @return array
     */
    public function applyOverduePenalties(): array
    {
        $results = [
            'processed' => 0,
            'penalties_applied' => 0,
            'errors' => 0,
        ];

        // Get all overdue installments
        $overdueInstallments = CreditInstallment::where('status', 'overdue')
            ->with('creditAccount')
            ->get();

        foreach ($overdueInstallments as $installment) {
            try {
                $results['processed']++;

                // Skip installments for accounts in grace period
                if ($installment->creditAccount->status === 'in_grace') {
                    continue;
                }

                // Apply penalty to this installment
                if ($this->applyPenaltyToInstallment($installment)) {
                    $results['penalties_applied']++;
                }
            } catch (\Exception $e) {
                $results['errors']++;
                Log::error('Error applying penalty to installment #' . $installment->id . ': ' . $e->getMessage());
            }
        }

        return $results;
    }

    /**
     * Apply a penalty to a specific overdue installment.
     *
     * @param CreditInstallment $installment
     * @return bool
     */
    public function applyPenaltyToInstallment(CreditInstallment $installment): bool
    {
        // Skip if not overdue
        if ($installment->status !== 'overdue') {
            return false;
        }

        // Get the late fee percentage from settings
        $lateFeePercentage = $this->getLateFeePercentage();

        if ($lateFeePercentage <= 0) {
            return false;
        }

        // Calculate daily penalty (annual rate / 365)
        $dailyPenaltyRate = $lateFeePercentage / 100 / 365;

        // Calculate penalty amount based on the outstanding amount
        $penaltyAmount = round($installment->amount_due * $dailyPenaltyRate, 2);

        // Skip if penalty is too small (e.g., less than 1 cent)
        if ($penaltyAmount < 0.01) {
            return false;
        }

        // Apply penalty within a transaction
        DB::transaction(function () use ($installment, $penaltyAmount) {
            // Create a debit entry for the penalty
            $this->createPenaltyEntry($installment, $penaltyAmount);

            // Increase the installment amount due
            $installment->amount_due += $penaltyAmount;
            $installment->save();
        });

        return true;
    }

    /**
     * Create a debit entry for a penalty.
     *
     * @param CreditInstallment $installment
     * @param float $penaltyAmount
     * @return AccountEntry
     */
    protected function createPenaltyEntry(CreditInstallment $installment, float $penaltyAmount): AccountEntry
    {
        // Get current balance
        $lastEntry = AccountEntry::where('creditable_type', CreditInstallment::class)
            ->orWhere('creditable_type', get_class($installment))
            ->orderBy('id', 'desc')
            ->first();

        $currentBalance = $lastEntry ? $lastEntry->balance_after : 0;

        // Create debit entry (penalty increases balance)
        $entry = new AccountEntry([
            'entry_type' => 'debit',
            'amount' => $penaltyAmount,
            'balance_after' => $currentBalance + $penaltyAmount,
        ]);

        $installment->accountEntries()->save($entry);

        return $entry;
    }

    /**
     * Check for installments that are due today and mark them as overdue.
     *
     * @return array
     */
    public function checkOverdueInstallments(): array
    {
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

        foreach ($overdueInstallments as $installment) {
            try {
                $results['checked']++;

                // Skip installments for accounts in grace period
                if ($installment->creditAccount->status === 'in_grace') {
                    continue;
                }

                // Mark as overdue
                $installment->status = 'overdue';
                $installment->save();

                // Also update the account status
                $installment->creditAccount->status = 'overdue';
                $installment->creditAccount->save();

                $results['marked_overdue']++;
            } catch (\Exception $e) {
                $results['errors']++;
                Log::error('Error marking installment #' . $installment->id . ' as overdue: ' . $e->getMessage());
            }
        }

        return $results;
    }

    /**
     * Get the late fee percentage from settings.
     *
     * @return float
     */
    protected function getLateFeePercentage(): float
    {
        $lateFeePercentage = GeneralProviderConfig::getLateFeePercentage();

        return is_numeric($lateFeePercentage) ? (float) $lateFeePercentage : 0;
    }
}
