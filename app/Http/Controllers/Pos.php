<?php

namespace App\Http\Controllers;

use App\Models\Box;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class Pos extends Controller
{
    public function index(): \Inertia\Response
    {
        return Inertia::render('Pos/MainContentPos', $this->getConfig());
    }

    private function getConfig(): array
    {
        $user = Auth::user();

        $box = Box::getUserBox($user->id);
        $box->dailyBoxes();
        return [
            "cashier" => auth()->user()->name,
            'config' => [
                'currency' => config('nova.currency'),
                'currencySymbol' => '$',
                'box' => $box,
                'todayBox' => $box->getTodayDailyBox(),
            ]
        ];
    }
}
