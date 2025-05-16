<?php

namespace App\Events;

use App\Models\Credit\CreditAccount;
use App\Models\Credit\CreditInstallment;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class InstallmentOverdue
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * The credit account that has an overdue installment.
     *
     * @var CreditAccount
     */
    public $creditAccount;

    /**
     * The installment that is overdue.
     *
     * @var CreditInstallment
     */
    public $installment;

    /**
     * Create a new event instance.
     *
     * @param CreditInstallment $installment
     * @return void
     */
    public function __construct(CreditInstallment $installment)
    {
        $this->installment = $installment;
    }
}
