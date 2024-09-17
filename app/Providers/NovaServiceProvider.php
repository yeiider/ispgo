<?php

namespace App\Providers;

use App\Models\User;
use App\Nova\Box;
use App\Nova\Customers\Address;
use App\Nova\Customers\Customer;
use App\Nova\Customers\TaxDetail;
use App\Nova\DailyBox;
use App\Nova\EmailTemplate;
use App\Nova\Finance\CashRegister;
use App\Nova\Finance\Expense;
use App\Nova\Finance\Income;
use App\Nova\Finance\Transaction;
use App\Nova\Installation;
use App\Nova\Inventory\Category;
use App\Nova\Inventory\EquipmentAssignment;
use App\Nova\Inventory\Product;
use App\Nova\Inventory\Supplier;
use App\Nova\Inventory\Warehouse;
use App\Nova\Invoice\CreditNote;
use App\Nova\Invoice\DailyInvoiceBalance;
use App\Nova\Invoice\Invoice;
use App\Nova\Invoice\PaymentPromise;
use App\Nova\Lenses\InstallationsLens;
use App\Nova\Lenses\TelephonicPlanLens;
use App\Nova\Lenses\TelephonicServiceLens;
use App\Nova\Lenses\TelevisionPlanLens;
use App\Nova\Lenses\TelevisionServiceLens;
use App\Nova\Lenses\UninstallationsLens;
use App\Nova\PageBuilder\Pages;
use App\Nova\Plan;
use App\Nova\Router;
use App\Nova\Service;
use App\Nova\Ticket;
use App\NovaPermissions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Ispgo\Mikrotik\Mikrotik;
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
                MenuSection::make(__('Customers & Services'), [
                    MenuGroup::make(__('All Customers'), [
                        MenuItem::resource(Customer::class),
                        MenuItem::resource(Address::class),
                        MenuItem::resource(TaxDetail::class),
                    ]),
                    MenuGroup::make(__('All Services'), [
                        MenuItem::resource(Service::class),
                        MenuItem::lens(Service::class, TelephonicServiceLens::class),
                        MenuItem::lens(Service::class, TelevisionServiceLens::class),
                        MenuItem::lens(Installation::class, InstallationsLens::class),
                        MenuItem::lens(Installation::class, UninstallationsLens::class),
                    ]),


                ])->icon('users')->collapsable(),

                MenuSection::make(__('Invoices'), [
                    MenuItem::resource(Invoice::class),
                    MenuItem::resource(CreditNote::class),
                    MenuItem::resource(PaymentPromise::class),
                    MenuItem::resource(DailyInvoiceBalance::class),
                ])->icon('archive')->collapsable(),
                MenuSection::make(__('Finances'), [
                    MenuItem::resource(CashRegister::class),
                    MenuItem::resource(Income::class),
                    MenuItem::resource(Expense::class),
                    MenuItem::resource(Transaction::class),
                    MenuItem::resource(Box::class),
                    MenuItem::resource(DailyBox::class),
                ])->icon('cash')->collapsable(),

                MenuSection::make(__('Tickets'), [
                    MenuItem::resource(Ticket::class)
                ])->icon('support')->collapsable(),

                MenuSection::make(__('Content'), [
                    MenuItem::resource(Pages::class),
                    MenuItem::resource(EmailTemplate::class)
                ])->icon('desktop-computer')->collapsable(),

                MenuSection::make(__('Inventory'), [
                    MenuItem::resource(Warehouse::class),
                    MenuItem::resource(Category::class),
                    MenuItem::resource(Product::class),
                    MenuItem::resource(Supplier::class),
                    MenuItem::resource(EquipmentAssignment::class),
                ])->icon('clipboard-list')->collapsable(),

                MenuSection::make('System Network', [
                    MenuItem::resource(Router::class),
                    MenuItem::resource(Plan::class),
                    MenuItem::lens(Plan::class, TelephonicPlanLens::class),
                    MenuItem::lens(Plan::class, TelevisionPlanLens::class),
                ])->icon('server')->collapsable(),

                MenuSection::make(__('Settings Manager'))
                    ->path('/settings-manager')
                    ->icon('cog')->canSee(function ($request) {
                        return $request->user() && $request->user()->can('Setting');
                    }),

                MenuSection::make(__('Mikrotik Manager'),[
                    MenuItem::link('Plans PPPoe','mikrotik/planes-ppp'),
                    MenuItem::link('Ip Pools','mikrotik/ip-pool'),
                ])->icon('cog')->collapsable(),

                $this->getNovaPermissionsMenu($request), // Agregar menú de NovaPermissions
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
            $allowedEmails = User::pluck('email')->toArray();
            return in_array($user->email, $allowedEmails);
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
            new SettingsManager,
            new NovaPermissions,
            new Mikrotik
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

    /**
     * Get the Nova Permissions menu.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Laravel\Nova\Menu\MenuSection
     */
    protected function getNovaPermissionsMenu(Request $request)
    {
        $novaPermissions = new NovaPermissions();

        return $novaPermissions->menu($request);
    }
}
