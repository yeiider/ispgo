<?php

namespace App\Nova\Finance;

use App\Nova\Resource;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Http\Requests\NovaRequest;

class ExpenseCategory extends Resource
{
    public static $model = \App\Models\Finance\ExpenseCategory::class;

    public static $title = 'name';

    public static $search = [
        'id', 'name', 'description'
    ];

    public function fields(NovaRequest $request): array
    {
        return [
            ID::make()->sortable(),
            Text::make(__('attribute.name'), 'name')->sortable()->rules('required', 'max:255'),
            Text::make(__('attribute.description'), 'description')->sortable(),
            
            HasMany::make(__('expense.expenses'), 'expenses', Expense::class),
        ];
    }

    public static function label() {
        return __('expense.expense_category');
    }
}
