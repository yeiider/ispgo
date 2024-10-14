<?php

namespace App\Nova\Metrics\Invoice;

use App\Models\Invoice\Invoice;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Trend;
use Illuminate\Support\Facades\DB;

class MonthlyRevenue extends Trend
{


    public function calculate(NovaRequest $request)
    {
        return $this->sumByMonths($request, Invoice::class, 'total');
    }

    public function uriKey()
    {
        return 'monthly-revenue';
    }

    public function name(): \Illuminate\Foundation\Application|array|string|\Illuminate\Contracts\Translation\Translator|null
    {
        return __('Monthly Revenue');
    }
}
