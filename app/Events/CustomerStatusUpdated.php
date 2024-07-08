<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CustomerStatusUpdated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $customer;


    /**
     * Create a new event instance.
     */
    public function __construct($customer)
    {
        $this->customer = $customer;
    }


}
