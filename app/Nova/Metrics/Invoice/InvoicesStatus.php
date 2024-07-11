<?php

namespace App\Nova\Metrics\Invoice;

use App\Models\Invoice\Invoice;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Partition;

class InvoicesStatus extends Partition
{
    public function calculate(NovaRequest $request)
    {
        return $this->count($request, Invoice::class, 'status');
    }

    public function uriKey()
    {
        return 'invoices-status';
    }
}
