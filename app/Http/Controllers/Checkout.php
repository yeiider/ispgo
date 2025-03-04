<?php

namespace App\Http\Controllers;

use App\Settings\GeneralProviderConfig;
use Illuminate\Http\Request;
use Inertia\Inertia;

class Checkout extends Controller
{
    public function index(): \Inertia\Response
    {
        return Inertia::render('Checkout', $this->getConfig());
    }

    private function getConfig(): array
    {
        $payment = session('payment_data');
        session()->forget('payment_data');
        $locale = env('APP_FAKER_LOCALE') ?? 'en_US';

        return [
            'config' => [
                'currency' => config('nova.currency'),
                'currencySymbol' => null,
                'companyEmail' => GeneralProviderConfig::getCompanyEmail(),
                'companyPhone' => GeneralProviderConfig::getCompanyPhone(),
                'locale' => str_replace('_', '-', $locale),
            ],
            'payment' => $payment
        ];
    }
}
