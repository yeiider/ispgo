<?php

namespace App\Settings\Config\Sources;

use Illuminate\Support\Facades\Auth;
use Ispgo\SettingsManager\Source\ConfigProviderInterface;

class Customer implements ConfigProviderInterface
{
    static public function getConfig(): array
    {
        $auth = Auth::guard('customer')->user();
        if (!$auth) {
            return [];
        }

        $customer = \App\Models\Customers\Customer::findOrFail($auth->getAuthIdentifier());

        return [
            'customer' => $customer,
            'sidebar' => self::getSideBar()
        ];
    }

    static function getSideBar(): array
    {
        return [
            'app_name' => config('app.name'),
            'url_logout' => route('customer.logout'),
            'links' => [
                [
                    'code' => "my_account",
                    'url' => route('dashboard'),
                    'title' => __('My Account'),
                ],
                [
                    'code' => 'my_orders',
                    'url' => route('orders'),
                    'title' => __('My Orders'),
                ],
                [
                    'code' => 'tickets',
                    'url' => route('tickets'),
                    'title' => __('Tickets'),
                ],
                [
                    'code' => 'payments',
                    'url' => '',//route('customer.addresses'),
                    'title' => __('Payments'),
                ],
                [
                    'code' => 'invoices',
                    'url' => '',
                    'title' => __('Invoices'),
                ],
                [
                    'code' => 'address_book',
                    'url' => '',//route('customer.addresses'),
                    'title' => __('Address Book'),
                ],

                [
                    'code' => 'account_information',
                    'url' => '',//route('customer.addresses'),
                    'title' => __('Account information'),
                ],
            ]
        ];
    }
}
