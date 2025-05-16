<?php

namespace App\Events;

use App\Models\Credit\CreditAccount;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CreditOpened
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * The credit account that was opened.
     *
     * @var CreditAccount
     */
    public $creditAccount;

    /**
     * The term in months for the credit.
     *
     * @var int
     */
    public $termMonths;

    /**
     * Create a new event instance.
     *
     * @param CreditAccount $creditAccount
     * @param int $termMonths
     * @return void
     */
    public function __construct(CreditAccount $creditAccount, int $termMonths)
    {
        $this->creditAccount = $creditAccount;
        $this->termMonths = $termMonths;
    }
}
