<?php

namespace App\Http\Middleware;

use App\Settings\Config\Sources\CustomerAccount;
use App\Settings\GeneralProviderConfig;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
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
        $config = CustomerAccount::getConfig();
        $customer = $config['customer'] ?? null;
        $sidebar = $config['sidebar'] ?? null;
        $flash = [
            'status' => $request->session()->get('status'),
        ];


        $translate = [
            'locale' => function () {
                return Lang::getLocale();
            },
            'language' => function () {
                return Lang::get('*');
            }
        ];
        $companyName = GeneralProviderConfig::getCompanyName();
        $isAuthenticated = auth('customer')->check();

        return array_merge(
            parent::share($request),
            compact('customer', 'sidebar', 'flash', 'translate', 'companyName', 'isAuthenticated')
        );
    }
}
