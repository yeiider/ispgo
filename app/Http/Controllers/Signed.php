<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Settings\GeneralProviderConfig;

class Signed extends Controller
{

    public function index(): \Inertia\Response
    {
        return Inertia::render('Signed/Index', [

        ]);
    }
}
