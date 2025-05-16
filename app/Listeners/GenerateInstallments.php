<?php

namespace App\Listeners;

use App\Events\CreditOpened;
use App\Services\Credit\InstallmentScheduler;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class GenerateInstallments implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * The installment scheduler instance.
     *
     * @var InstallmentScheduler
     */
    protected $installmentScheduler;

    /**
     * Create the event listener.
     *
     * @param InstallmentScheduler $installmentScheduler
     * @return void
     */
    public function __construct(InstallmentScheduler $installmentScheduler)
    {
        $this->installmentScheduler = $installmentScheduler;
    }

    /**
     * Handle the event.
     *
     * @param CreditOpened $event
     * @return void
     */
    public function handle(CreditOpened $event): void
    {
        // Generate and save installments for the credit account
        $this->installmentScheduler->createInstallments(
            $event->creditAccount,
            $event->termMonths
        );
    }
}
