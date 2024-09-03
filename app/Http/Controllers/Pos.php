<?php

namespace App\Http\Controllers;

use Inertia\Inertia;

class Pos extends Controller
{
    public function index(): \Inertia\Response
    {
        return Inertia::render('Pos/MainContentPos',$this->getConfig());
    }

    private function getConfig(): array
    {
        return [
            "cashier" => auth()->user()->name,
            'config' => [
                'currency' => config('nova.currency'),
                'currencySymbol' => '$',
            ]
        ];
    }
}
