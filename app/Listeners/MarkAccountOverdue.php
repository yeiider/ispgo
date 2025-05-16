<?php

namespace App\Listeners;

use App\Events\InstallmentOverdue;
use App\Models\Credit\CreditAccount;
use App\Models\Credit\CreditInstallment;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class MarkAccountOverdue implements ShouldQueue
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
     * @param InstallmentOverdue $event
     * @return void
     */
    public function handle(InstallmentOverdue $event): void
    {
        try {
            $creditAccount = $event->installment->creditAccount;
            $installment = $event->installment;

            // Skip if account is already in grace period
            if ($creditAccount->status === 'in_grace') {
                Log::info("Credit account #{$creditAccount->id} is in grace period, skipping overdue marking.");
                return;
            }

            // Mark the account as overdue
            $creditAccount->status = 'overdue';
            $creditAccount->save();

            // Log the status change
            Log::info("Credit account #{$creditAccount->id} marked as overdue due to installment #{$installment->id}");

            // Send notification to customer
            //$this->sendOverdueNotification($creditAccount, $installment);
        } catch (\Exception $e) {
            // Log any errors
            Log::error("Error marking account as overdue: " . $e->getMessage());

            // Fail the job so it can be retried
            $this->fail($e);
        }
    }

    /**
     * Send an overdue notification to the customer.
     *
     * @param CreditAccount $creditAccount
     * @param CreditInstallment $installment
     * @return void
     */
    protected function sendOverdueNotification($creditAccount, $installment): void
    {
        // Check if the notification class exists
        if (class_exists(InstallmentOverdueNotification::class)) {
            try {
                // Send notification to the customer
                $customer = $creditAccount->customer;

                if ($customer) {
                    Notification::send($customer, new InstallmentOverdueNotification($creditAccount, $installment));
                    Log::info("Overdue notification sent to customer #{$customer->id}");
                }
            } catch (\Exception $e) {
                // Just log the error but don't fail the job
                Log::error("Error sending overdue notification: " . $e->getMessage());
            }
        } else {
            Log::info("InstallmentOverdueNotification class not found, skipping notification.");
        }
    }
}
