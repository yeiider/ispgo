<?php

namespace App\Providers;

use App\Models\User;
use App\Nova\Box;
use App\Nova\Contract;
use App\Nova\Customers\Address;
use App\Nova\Customers\Customer;
use App\Nova\Customers\TaxDetail;
use App\Nova\DailyBox;
use App\Nova\Dashboards\Main;
use App\Nova\EmailTemplate;
use App\Nova\Finance\CashRegister;
use App\Nova\Finance\Expense;
use App\Nova\Finance\Income;
use App\Nova\Finance\Transaction;
use App\Nova\HtmlTemplate;
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
use Ispgo\Smartolt\Smartolt;
use Laravel\Nova\Exceptions\NovaException;
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
                MenuSection::make(__('panel.customers_and_services'), [
                    MenuGroup::make(__('panel.all_customers'), [
                        MenuItem::resource(Customer::class)->name(__('panel.customers')),
                        MenuItem::resource(Address::class)->name(__('panel.addresses')),
                        MenuItem::resource(TaxDetail::class)->name(__('panel.tax_details')),
                    ]),
                    MenuGroup::make(__('panel.all_services'), [
                        MenuItem::resource(Service::class),
                        MenuItem::resource(Contract::class)->name(__('panel.contract')),
                        MenuItem::lens(Service::class, TelephonicServiceLens::class)->name(__('panel.telephonic_services')),
                        MenuItem::lens(Service::class, TelevisionServiceLens::class)->name(__('panel.television_services')),
                        MenuItem::lens(Installation::class, InstallationsLens::class)->name(__('panel.installations')),
                        MenuItem::lens(Installation::class, UninstallationsLens::class)->name(__('panel.uninstallations')),
                    ]),


                ])->icon('users')->collapsable(),

                MenuSection::make(__('panel.invoices'), [
                    MenuItem::resource(Invoice::class)->name(__('panel.invoices')),
                    MenuItem::resource(CreditNote::class)->name(__('panel.credit_notes')),
                    MenuItem::resource(PaymentPromise::class)->name(__('panel.payment_promises')),
                    MenuItem::resource(DailyInvoiceBalance::class)->name(__('panel.daily_invoice_balances')),
                ])->icon('archive')->collapsable(),
                MenuSection::make(__('panel.finances'), [
                    MenuItem::resource(CashRegister::class)->name(__('panel.cash_registers'))->canSee(function ($request) {
                        return $request->user() && $request->user()->can('PostInvoice');
                    }),
                    MenuItem::resource(Income::class)->name(__('panel.incomes')),
                    MenuItem::resource(Expense::class)->name(__('panel.expenses')),
                    MenuItem::resource(Transaction::class)->name(__('panel.transactions')),
                    MenuItem::resource(Box::class)->name(__('panel.boxes')),
                    MenuItem::resource(DailyBox::class)->name(__('panel.daily_boxes')),
                ])->icon('cash')->collapsable(),

                MenuSection::make(__('panel.tickets'), [
                    MenuItem::resource(Ticket::class)->name(__('panel.tickets')),
                ])->icon('support')->collapsable(),

                MenuSection::make(__('panel.content'), [
                    MenuItem::resource(Pages::class)->name(__('panel.pages')),
                    MenuItem::resource(EmailTemplate::class)->name(__('panel.email_templates')),
                    MenuItem::resource(HtmlTemplate::class)->name(__('Html Template')),
                ])->icon('desktop-computer')->collapsable(),

                MenuSection::make(__('panel.inventory'), [
                    MenuItem::resource(Warehouse::class)->name(__('panel.warehouses')),
                    MenuItem::resource(Category::class)->name(__('panel.categories')),
                    MenuItem::resource(Product::class)->name(__('panel.products')),
                    MenuItem::resource(Supplier::class)->name(__('panel.suppliers')),
                    MenuItem::resource(EquipmentAssignment::class)->name(__('panel.equipment_assignments')),
                ])->icon('clipboard-list')->collapsable(),

                MenuSection::make(__('panel.system_network'), [
                    MenuItem::resource(Router::class)->name(__('panel.routers')),
                    MenuItem::resource(Plan::class)->name(__('panel.plans')),
                    MenuItem::lens(Plan::class, TelevisionPlanLens::class)->name(__('panel.television_plans')),
                ])->icon('server')->collapsable(),

                MenuSection::make(__('panel.settings_manager'))
                    ->path('/settings-manager')
                    ->icon('cog')->canSee(function ($request) {
                        return $request->user() && $request->user()->can('Setting');
                    }),

                MenuSection::make(__('panel.mikrotik_manager'), [
                    MenuItem::link(__('panel.plans_PPPoe'), 'mikrotik/planes-ppp'),
                    MenuItem::link(__('panel.ip_pools'), 'mikrotik/ip-pool'),
                    MenuItem::link(__('panel.ipv6_pools'), 'mikrotik/ipv6-pool'),
                    MenuItem::link(__('panel.DHCP_server_Ipv6'), 'mikrotik/dhcp-serve'),
                ])->icon('cog')->collapsable(),

                $this->getNovaPermissionsMenu($request),
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
    protected function dashboards(): array
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
    public function tools(): array
    {
        return [
            new SettingsManager,
            new NovaPermissions,
            new Mikrotik,
            new Smartolt
        ];
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        parent::register();
    }

    /**
     * Get the Nova Permissions menu.
     *
     * @param Request $request
     * @return MenuSection
     * @throws NovaException
     */
    protected function getNovaPermissionsMenu(Request $request): MenuSection
    {
        $novaPermissions = new NovaPermissions();

        return $novaPermissions->menu($request);
    }
}
