<?php

namespace App\Nova\Customers;

use App\Mail\DynamicEmail;
use App\Models\Customers\DocumentType;
use App\Models\EmailTemplate;
use App\Nova\Actions\UpdateCustomerStatus;
use App\Nova\Contract;
use App\Nova\Filters\CustomerStatus;
use App\Nova\Invoice\Invoice;
use App\Nova\Metrics\NewCustomers;
use App\Nova\Resource;
use App\Nova\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Mail;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Badge;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\HasOne;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Tabs\Tab;

class Customer extends Resource
{
    public static $model = \App\Models\Customers\Customer::class;

    public static $title = 'full_name';

    public static $search = [
        'id', 'first_name', 'last_name', 'email_address', 'phone_number'
    ];

    public function fields(Request $request): array
    {
        return [
            Tab::group(__('Information'), [
                Tab::make(__('Personal Information'), [
                    ID::make()->sortable(),
                    Text::make(__('customer.first_name'), 'first_name')->sortable()->rules('required', 'max:100'),
                    Text::make(__('customer.last_name'), 'last_name')->sortable()->rules('required', 'max:100'),
                    Date::make(__('customer.date_of_birth'), 'date_of_birth')->nullable(),
                    Text::make(__('customer.phone_number'), 'phone_number')->rules('required', 'max:12'),
                    Text::make(__('customer.email_address'), 'email_address')->sortable()->rules('required', 'email', 'max:100'),
                    Select::make(__('customer.document_type'), 'document_type')
                        ->options(DocumentType::pluck('name', 'code')->toArray())
                        ->sortable()
                        ->rules('required', 'max:20'),
                    Text::make(__('customer.identity_document'), 'identity_document')->rules('required', 'max:100'),
                    Badge::make(__('customer.status'), 'customer_status')->map([
                        'active' => 'success',
                        'inactive' => 'danger',
                        'suspended' => 'warning',
                    ])->icons([
                        'danger' => 'exclamation-circle',
                        'success' => 'check-circle'
                    ])->label(function ($value) {
                        return __('attribute.customer_status.' . $value);
                    }),

                    Select::make(__('customer.customer_status'), 'customer_status')
                        ->options([
                            'active' => __('customer.active'),
                            'inactive' => __('customer.inactive')
                        ])
                        ->displayUsingLabels()
                        ->hideFromIndex()
                        ->sortable()->rules('required'),

                    Textarea::make(__('customer.additional_notes'), 'additional_notes')->nullable(),
                    HasOne::make(__('customer.taxDetails'), 'taxDetails', TaxDetail::class),
                    HasMany::make(__('customer.addresses'), 'addresses', Address::class),
                ]),

                Tab::make(__('Services'), [
                    HasMany::make(__('services'), 'services', Service::class),
                ]),

                Tab::make(__('Invoices'), [
                    HasMany::make(__('Invoices'), 'invoices', Invoice::class),
                ]),
                Tab::make(__('Contracts'), [
                    HasMany::make(__('Ccontracts'), 'contracts', Contract::class),
                ]),



            ]),
        ];
    }

    public function actions(NovaRequest $request): array
    {
        return [
            new UpdateCustomerStatus,
            Action::using(__('customer.send_email'), function (ActionFields $fields, Collection $models) {
                $model = $models->first();
                $template = EmailTemplate::find(3); // ID de la plantilla de bienvenida
                Mail::to($model->email_address)->send(new DynamicEmail(["customer" => $model], $template));
            })->showInline()
        ];
    }

    public static function label(): \Illuminate\Foundation\Application|array|string|\Illuminate\Contracts\Translation\Translator|null
    {
        return __('customer.customer');
    }

    public static function authorizedToCreate(Request $request): bool
    {
        return auth()->check() && $request->user()->can('createCustomer');
    }

    public function authorizedToUpdate(Request $request): bool
    {
        return auth()->check() && $request->user()->can('updateCustomer', $this->resource);
    }

    public function authorizedToDelete(Request $request): bool
    {
        return auth()->check() && $request->user()->can('deleteCustomer', $this->resource);
    }

    public static function authorizedToViewAny(Request $request)
    {
        return auth()->check() && $request->user()->can('viewAnyCustomer');
    }

    public function authorizedToView(Request $request)
    {
        return auth()->check() && $request->user()->can('viewCustomer', $this->resource);
    }

    public function filters(Request $request)
    {
        return [
            new CustomerStatus,
        ];
    }

    /**
     * Get the cards available for the resource.
     *
     * @param NovaRequest $request
     * @return array
     */
    public function cards(NovaRequest $request): array
    {
        return [
            new NewCustomers
        ];
    }
}
