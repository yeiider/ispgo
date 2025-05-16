<?php

namespace App\Services\Credit;

use App\Events\PaymentReceived;
use App\Models\Credit\AccountEntry;
use App\Models\Credit\CreditAccount;
use App\Models\Credit\CreditInstallment;
use App\Models\Credit\CreditPayment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class PaymentService
{
    /**
     * Apply a payment to a credit account.
     *
     * @param CreditAccount $account
     * @param array $paymentData
     * @return CreditPayment
     * @throws ValidationException
     */
    public function applyPayment(CreditAccount $account, array $paymentData): CreditPayment
    {
        // Validate payment data
        $validator = Validator::make($paymentData, [
            'amount' => 'required|numeric|min:0.01',
            'method' => 'required|string',
            'reference' => 'nullable|string',
            'notes' => 'nullable|string',
            'paid_at' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        // Set paid_at to now if not provided
        if (!isset($paymentData['paid_at'])) {
            $paymentData['paid_at'] = now();
        }

        // Create payment record
        $payment = new CreditPayment([
            'credit_account_id' => $account->id,
            'amount' => $paymentData['amount'],
            'method' => $paymentData['method'],
            'reference' => $paymentData['reference'] ?? null,
            'notes' => $paymentData['notes'] ?? null,
            'paid_at' => $paymentData['paid_at'],
        ]);

        // Process payment within a transaction
        DB::transaction(function () use ($account, $payment) {
            $payment->save();

            // Create credit entry for the payment
            $this->createCreditEntry($payment);

            // Apply payment to pending installments (FIFO)
            $this->applyPaymentToInstallments($account, $payment);

            // Update account status if needed
            $this->updateAccountStatus($account);

            // Dispatch payment received event
            event(new PaymentReceived($account, $payment));
        });

        return $payment;
    }

    /**
     * Create a credit entry for a payment.
     *
     * @param CreditPayment $payment
     * @return AccountEntry
     */
    protected function createCreditEntry(CreditPayment $payment): AccountEntry
    {
        // Get current balance
        $lastEntry = AccountEntry::where('creditable_type', CreditPayment::class)
            ->orWhere('creditable_type', CreditInstallment::class)
            ->orderBy('id', 'desc')
            ->first();

        $currentBalance = $lastEntry ? $lastEntry->balance_after : 0;

        // Create credit entry (payment reduces balance)
        $entry = new AccountEntry([
            'entry_type' => 'credit',
            'amount' => $payment->amount,
            'balance_after' => $currentBalance - $payment->amount,
        ]);

        $payment->accountEntries()->save($entry);

        return $entry;
    }

    /**
     * Apply payment to pending installments using FIFO method.
     *
     * @param CreditAccount $account
     * @param CreditPayment $payment
     * @return void
     */
    protected function applyPaymentToInstallments(CreditAccount $account, CreditPayment $payment): void
    {
        $remainingAmount = $payment->amount;

        // Get pending installments ordered by due date (oldest first)
        $pendingInstallments = $account->installments()
            ->where('status', 'pending')
            ->orWhere('status', 'overdue')
            ->orderBy('due_date')
            ->get();

        foreach ($pendingInstallments as $installment) {
            if ($remainingAmount <= 0) {
                break;
            }

            // If payment covers the full installment
            if ($remainingAmount >= $installment->amount_due) {
                $installment->status = 'paid';
                $installment->save();
                $remainingAmount -= $installment->amount_due;
            } else {
                // Partial payment - create a record but keep installment as pending
                // In a real system, you might handle partial payments differently
                // For simplicity, we're not changing the installment status for partial payments
                break;
            }
        }

        // If there's remaining amount, it becomes credit for future installments
        if ($remainingAmount > 0) {
            // You could store this as credit for future use
            // For simplicity, we're just noting it in the payment
            $payment->notes = ($payment->notes ? $payment->notes . "\n" : '') .
                "Excess payment of $remainingAmount applied as credit for future installments.";
            $payment->save();
        }
    }

    /**
     * Update the account status based on its installments.
     *
     * @param CreditAccount $account
     * @return void
     */
    protected function updateAccountStatus(CreditAccount $account): void
    {
        // Check if all installments are paid
        $allPaid = $account->installments()->where('status', '!=', 'paid')->count() === 0;

        if ($allPaid) {
            $account->status = 'closed';
            $account->save();
            return;
        }

        // Check if any installments are overdue
        $hasOverdue = $account->installments()->where('status', 'overdue')->exists();

        if ($hasOverdue) {
            $account->status = 'overdue';
        } else {
            $account->status = 'active';
        }

        $account->save();
    }

    /**
     * Get payment history for a credit account.
     *
     * @param CreditAccount $account
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getPaymentHistory(CreditAccount $account)
    {
        return $account->payments()->orderBy('paid_at', 'desc')->get();
    }

    /**
     * Get a ledger (account entries) for a credit account.
     *
     * @param CreditAccount $account
     * @return array
     */
    public function getLedger(CreditAccount $account): array
    {
        // Get all entries sorted by creation date
        $entries = AccountEntry::where(function ($query) use ($account) {
                $query->whereHasMorph('creditable', [CreditInstallment::class], function ($q) use ($account) {
                    $q->where('credit_account_id', $account->id);
                })->orWhereHasMorph('creditable', [CreditPayment::class], function ($q) use ($account) {
                    $q->where('credit_account_id', $account->id);
                });
            })
            ->orderBy('created_at')
            ->get();

        // Format entries for display
        return $entries->map(function ($entry) {
            $type = $entry->creditable_type === CreditInstallment::class ? 'Installment' : 'Payment';
            $description = $entry->creditable_type === CreditInstallment::class
                ? 'Installment due ' . $entry->creditable->due_date->format('Y-m-d')
                : 'Payment via ' . $entry->creditable->method;

            return [
                'date' => $entry->created_at->format('Y-m-d H:i:s'),
                'type' => $type,
                'description' => $description,
                'entry_type' => $entry->entry_type,
                'amount' => $entry->amount,
                'balance_after' => $entry->balance_after,
            ];
        })->toArray();
    }
}
