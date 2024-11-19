<?php

namespace App\Http\Controllers\CustomerAccount;

use App\Http\Controllers\Controller;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index(): \Inertia\Response
    {
        return Inertia::render('Customer/Dashboard', [
            'route_edit_customer' => route('customer.edit'),
        ]);
    }

    public function orders(): \Inertia\Response
    {
        return Inertia::render('Customer/Orders');
    }
}
