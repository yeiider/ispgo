<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Settings\GeneralProviderConfig;
class Welcome extends Controller
{

    public function index(): \Inertia\Response
    {
        return Inertia::render('Welcome', [
            'loginUrl' => route('customer.login'),
            'registerUrl' => route('customer.register'),
            'isAuthenticated' => auth('customer')->check(),
            'customerDashboardUrl' => route('index'),
            'companyName' => GeneralProviderConfig::getCompanyName()
        ]);
    }
}
