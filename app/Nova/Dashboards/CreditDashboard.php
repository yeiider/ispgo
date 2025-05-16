<?php

namespace App\Nova\Dashboards;

use App\Nova\Credit\Metrics\CreditAccountsByStatus;
use App\Nova\Credit\Metrics\PaymentsTrend;
use App\Nova\Credit\Metrics\TotalCreditPortfolio;
use Laravel\Nova\Cards\Help;
use Laravel\Nova\Dashboard;

class CreditDashboard extends Dashboard
{
    /**
     * Get the cards for the dashboard.
     *
     * @return array
     */
    public function cards()
    {
        return [
            new TotalCreditPortfolio(),
            new CreditAccountsByStatus(),
            new PaymentsTrend(),
            (new Help)->width('full')->withHeader('Credit Module Help')
                ->withContent('This dashboard provides an overview of the credit module. You can see the total credit portfolio, the distribution of credit accounts by status, and the trend of payments over time.'),
        ];
    }

    /**
     * Get the URI key for the dashboard.
     *
     * @return string
     */
    public function uriKey()
    {
        return 'credit-dashboard';
    }

    /**
     * Get the displayable name of the dashboard.
     *
     * @return string
     */
    public function name()
    {
        return 'Credit Dashboard';
    }

    /**
     * Get the component name for the dashboard.
     *
     * @return string
     */
    public function component()
    {
        return 'dashboard';
    }
}
