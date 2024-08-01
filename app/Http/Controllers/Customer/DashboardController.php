<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        return Inertia::render('Customer/Dashboard');
    }

    public function orders()
    {
        return Inertia::render('Customer/Orders');
    }
}
