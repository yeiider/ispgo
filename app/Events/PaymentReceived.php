<?php

namespace App\Events;

use App\Models\Credit\CreditAccount;
use App\Models\Credit\CreditPayment;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PaymentReceived
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * The credit account that received the payment.
     *
     * @var CreditAccount
     */
    public $creditAccount;

    /**
     * The payment that was received.
     *
     * @var CreditPayment
     */
    public $payment;

    /**
     * Create a new event instance.
     *
     * @param CreditAccount $creditAccount
     * @param CreditPayment $payment
     * @return void
     */
    public function __construct(CreditAccount $creditAccount, CreditPayment $payment)
    {
        $this->creditAccount = $creditAccount;
        $this->payment = $payment;
    }
}
