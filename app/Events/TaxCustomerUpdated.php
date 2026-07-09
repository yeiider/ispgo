<?php

namespace App\Events;

use App\Models\Customers\TaxDetail;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TaxCustomerUpdated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public TaxDetail $taxDetail;

    public function __construct(TaxDetail $taxDetail)
    {
        $this->taxDetail = $taxDetail;
    }
}
