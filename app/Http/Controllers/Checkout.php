<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class Checkout extends Controller
{
    public function index(): \Inertia\Response
    {
        $props = [];
        $payment = session('payment_data');
        if ($payment) {
            $props = ["payment" => $payment];
        }
        session()->forget('payment_data');

        return Inertia::render('Checkout', $props);
    }
}
