<?php

namespace App\Settings\Config\Sources;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Ispgo\SettingsManager\Source\ConfigProviderInterface;

class CustomerAccount implements ConfigProviderInterface
{
    static public function getConfig(): array
    {
        $auth = Auth::guard('customer')->user();
        if (!$auth) {
            return [];
        }

        $customer = \App\Models\Customers\Customer::findOrFail($auth->getAuthIdentifier());

        $date = new \DateTime($customer->date_of_birth);
        $customer->date_of_birth_formatted = $date->format('Y-m-d');


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
                    'url' => route('index'),
                    'title' => __('My Account'),
                    'icon' => 'User',
                ],
                [
                    'code' => 'tickets',
                    'url' => route('tickets'),
                    'title' => __('Tickets'),
                    'icon' => 'Tickets'
                ],
                [
                    'code' => 'invoices',
                    'url' => route('invoices'),
                    'title' => __('Invoices'),
                    'icon' => 'FileText'
                ],
                [
                    'code' => 'address_book',
                    'url' => route('addresses'),
                    'title' => __('Address Book'),
                    'icon' => 'NotebookTabs'
                ],
            ]
        ];
    }
}
