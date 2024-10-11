<?php

namespace App\Nova\Invoice;

use App\Nova\Resource;
use App\Nova\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Textarea;

class CreditNote extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Invoice\CreditNote::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'id';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param Request $request
     * @return array
     */
    public function fields(Request $request): array
    {
        return [
            ID::make()->sortable(),
            BelongsTo::make(__('credit_note.invoice'), 'invoice', Invoice::class)->searchable(),
            BelongsTo::make(__('credit_note.user'), 'user', User::class)
                ->default(Auth::id())->readonly()

            ,
            Currency::make(__('credit_note.amount'), 'amount')->step(0.01)->rules('required', 'numeric', 'min:0'),
            Date::make(__('credit_note.issue_date'), 'issue_date')->rules('required', 'date'),
            Textarea::make(__('credit_note.reason'), 'reason')->rules('nullable', 'string', 'max:255'),
        ];
    }

    public static function label(): \Illuminate\Foundation\Application|array|string|\Illuminate\Contracts\Translation\Translator|null
    {
        return __('credit_note.credit_note');
    }

    public static function authorizedToCreate(Request $request)
    {
        return auth()->check() && $request->user()->can('updateCreditNote');

    }

    public function authorizedToUpdate(Request $request)
    {
        return auth()->check() && $request->user()->can('updateCreditNote', $this->resource);
    }

    public function authorizedToDelete(Request $request)
    {
        return auth()->check() && $request->user()->can('deleteCreditNote', $this->resource);
    }

    public static function authorizedToViewAny(Request $request)
    {
        return auth()->check() && $request->user()->can('viewAnyCreditNote');
    }

    public function authorizedToView(Request $request)
    {
        return auth()->check() && $request->user()->can('viewCreditNote', $this->resource);
    }
}
