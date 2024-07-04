<?php

namespace App\Nova;

use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Resource;
use Illuminate\Http\Request;

class Invoice extends Resource
{
    public static $model = \App\Models\Invoice::class;

    public static $title = 'id';

    public static $search = [
        'id', 'amount', 'issue_date', 'status'
    ];

    public function fields(NovaRequest $request)
    {
        return [
            ID::make(__('ID'), 'id')->sortable(),

            BelongsTo::make('Customer', 'customer', \App\Nova\Customer::class),
            BelongsTo::make('Service', 'service', \App\Nova\Service::class),
            BelongsTo::make('User', 'user', \App\Nova\User::class),

            Number::make('Subtotal', 'subtotal')->step(0.01),
            Number::make('Tax', 'tax')->step(0.01),
            Number::make('Total', 'total')->step(0.01),
            Number::make('Amount', 'amount')->step(0.01),
            Date::make('Issue Date', 'issue_date'),
            Date::make('Due Date', 'due_date'),
            Select::make('Status', 'status')->options([
                'paid' => 'Paid',
                'unpaid' => 'Unpaid',
                'overdue' => 'Overdue'
            ])->displayUsingLabels(),
            Text::make('Payment Method', 'payment_method'),
            Textarea::make('Notes', 'notes')->hideFromIndex(),
        ];
    }

    public function actions(NovaRequest $request)
    {
        return [];
    }

   /* public static function authorizedToCreate(Request $request)
    {
        return false;
    }
    public function authorizedToUpdate(Request $request)
    {
        return false;
    }

    public function authorizedToDelete(Request $request)
    {
        return false;
    }*/

}
