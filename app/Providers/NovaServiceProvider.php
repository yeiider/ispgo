<?php

namespace App\Providers;

use App\Nova\Address;
use App\Nova\Customer;
use App\Nova\InternetPlan;
use App\Nova\Invoice;
use App\Nova\Router;
use App\Nova\Service;
use App\Nova\TaxDetail;
use Badinansoft\LanguageSwitch\LanguageSwitch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Ispgo\SettingsManager\SettingsManager;
use Laravel\Nova\Dashboards\Main;
use Laravel\Nova\Menu\MenuGroup;
use Laravel\Nova\Menu\MenuItem;
use Laravel\Nova\Menu\MenuSection;
use Laravel\Nova\Nova;
use Laravel\Nova\NovaApplicationServiceProvider;

class NovaServiceProvider extends NovaApplicationServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
        Nova::userLocale(function () {
            return match (app()->getLocale()) {
                'en' => 'en-US',
                'es' => 'es-CO',
                default => null,
            };
        });
        Nova::mainMenu(function (Request $request) {
            return [
                MenuSection::dashboard(Main::class)->icon('chart-bar'),
                // customers
                MenuSection::make('Customers', [
                        MenuItem::resource(Customer::class),
                        MenuItem::resource(Address::class),
                        MenuItem::resource(TaxDetail::class),
                        MenuItem::resource(Service::class)
                ])->icon('users')->collapsable(),

                MenuSection::make('Invoices', [
                    MenuItem::resource(Invoice::class),
                ])->icon('cash')->collapsable(),

                MenuSection::make('System Network', [
                    MenuItem::resource(Router::class),
                    MenuItem::resource(InternetPlan::class),
                ])->icon('server')->collapsable(),

                MenuSection::make('Settings Manager')
                    ->path('/settings-manager')
                    ->icon('cog'),
                /* MenuSection::make('Content', [
                     MenuItem::resource(Series::class),
                     MenuItem::resource(Release::class),
                 ])->icon('document-text')->collapsable(),*/
            ];
        });
    }

    /**
     * Register the Nova routes.
     *
     * @return void
     */
    protected function routes()
    {
        Nova::routes()
            ->withAuthenticationRoutes(default: true)
            ->withPasswordResetRoutes()
            ->register();
    }

    /**
     * Register the Nova gate.
     *
     * This gate determines who can access Nova in non-local environments.
     *
     * @return void
     */
    protected function gate()
    {
        Gate::define('viewNova', function ($user) {
            return in_array($user->email, [
                //
            ]);
        });
    }

    /**
     * Get the dashboards that should be listed in the Nova sidebar.
     *
     * @return array
     */
    protected function dashboards()
    {
        return [
            new \App\Nova\Dashboards\Main,
        ];
    }

    /**
     * Get the tools that should be listed in the Nova sidebar.
     *
     * @return array
     */
    public function tools()
    {
        return [
            new SettingsManager
        ];
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
