<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class Checkout extends Controller
{
    public function index(): \Inertia\Response
    {
        return Inertia::render('Checkout');
    }
}
