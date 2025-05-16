<?php

namespace App\Listeners;

use App\Events\PaymentReceived;
use App\Models\Credit\AccountEntry;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class UpdateLedger implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param PaymentReceived $event
     * @return void
     */
    public function handle(PaymentReceived $event): void
    {
        try {
            // Get the credit account and payment
            $creditAccount = $event->creditAccount;
            $payment = $event->payment;

            // Recalculate the balance for all entries
            $this->recalculateBalances($creditAccount->id);

            // Log the successful update
            Log::info("Ledger updated for credit account #{$creditAccount->id} after payment #{$payment->id}");
        } catch (\Exception $e) {
            // Log any errors
            Log::error("Error updating ledger: " . $e->getMessage());

            // Fail the job so it can be retried
            $this->fail($e);
        }
    }

    /**
     * Recalculate balances for all entries related to a credit account.
     *
     * @param int $creditAccountId
     * @return void
     */
    protected function recalculateBalances(int $creditAccountId): void
    {
        // Get all entries for this credit account, ordered by creation date
        $entries = AccountEntry::whereHasMorph('creditable', ['App\Models\Credit\CreditInstallment', 'App\Models\Credit\CreditPayment'], function ($query) use ($creditAccountId) {
                $query->where('credit_account_id', $creditAccountId);
            })
            ->orderBy('created_at')
            ->get();

        // Recalculate balances
        $balance = 0;
        foreach ($entries as $entry) {
            if ($entry->entry_type === 'debit') {
                $balance += $entry->amount;
            } else {
                $balance -= $entry->amount;
            }

            // Update the balance_after field
            $entry->balance_after = $balance;
            $entry->save();
        }
    }
}
