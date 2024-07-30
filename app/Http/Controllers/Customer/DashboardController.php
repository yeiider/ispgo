<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        return Inertia::render('Dashboard', [
            'sidebar' => [
                'links' => [
                    'address_book' => [
                        'link' => '',//route('customer.addresses'),
                        'title' => __('Address Book'),
                        'is_active' => true,
                    ]
                ]
            ]
        ]);
    }
}
