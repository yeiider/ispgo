<?php

namespace App\Nova\Dashboards;

use App\Nova\Metrics\Invoice\InvoicesStatus;
use App\Nova\Metrics\Invoice\MonthlyRevenue;
use App\Nova\Metrics\Invoice\OutstandingBalance;
use App\Nova\Metrics\Invoice\TotalRevenue;
use App\Nova\Metrics\NewCustomers;
use Laravel\Nova\Dashboards\Main as Dashboard;

class Main extends Dashboard
{

    public function name(): \Illuminate\Foundation\Application|array|string|\Illuminate\Contracts\Translation\Translator|null
    {
        return __('Dashboard');
    }

    /**
     * Get the cards for the dashboard.
     *
     * @return array
     */
    public function cards(): array
    {
        return [
            (new NewCustomers())->width('1/2'),
            (new InvoicesStatus())->width('1/2'),
            (new TotalRevenue())->width('1/2'),
            (new OutstandingBalance())->width('1/2'),
            (new MonthlyRevenue())->width('1/2'),
        ];
    }
}
