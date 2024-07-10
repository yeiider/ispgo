<?php

namespace App\Nova\Metrics\Invoice;

use App\Models\Invoice\Invoice;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Value;

class TotalRevenue extends Value
{

    /**
     * The element's icon.
     *
     * @var string
     */
    public $icon = 'cash';

    public function calculate(NovaRequest $request)
    {
        return $this->sum($request, Invoice::class, 'amount');
    }

    public function ranges()
    {
        return [
            30 => __('30 Days'),
            60 => __('60 Days'),
            365 => __('365 Days'),
            'TODAY' => __('Today'),
            'MTD' => __('Month To Date'),
            'QTD' => __('Quarter To Date'),
            'YTD' => __('Year To Date'),
        ];
    }

    public function uriKey()
    {
        return 'total-revenue';
    }
}
