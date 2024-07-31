<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return array_merge(parent::share($request), [

            'sidebar' => [
                'app_name' => config('app.name'),
                'links' => [
                    [
                        'code' => "my_account",
                        'url' => route('dashboard'),
                        'title' => __('My Account'),
                        'is_active' => true,
                    ],
                    [
                        'code' => 'my_orders',
                        'url' => route('orders'),
                        'title' => __('My Orders'),
                        'is_active' => false,
                    ],
                    [
                        'code' => 'payments',
                        'url' => '',//route('customer.addresses'),
                        'title' => __('Payments'),
                        'is_active' => false,
                    ],
                    [
                        'code' => 'invoices',
                        'url' => '',
                        'title' => __('Invoices'),
                        'is_active' => false,
                    ],
                    [
                        'code' => 'address_book',
                        'url' => '',//route('customer.addresses'),
                        'title' => __('Address Book'),
                        'is_active' => false,
                    ],

                    [
                        'code' => 'account_information',
                        'url' => '',//route('customer.addresses'),
                        'title' => __('Account information'),
                        'is_active' => false,
                    ],
                ]
            ]
        ]);
    }
}
