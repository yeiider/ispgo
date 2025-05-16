<?php

namespace App\Nova\Credit;

use App\Models\Credit\CreditAccountProduct as CreditAccountProductModel;
use App\Models\Inventory\Product;
use App\Nova\Resource;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class CreditAccountProduct extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<CreditAccountProductModel>
     */
    public static $model = CreditAccountProductModel::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'id';

    /**
     * Indicates if the resource should be displayed in the sidebar.
     *
     * @var bool
     */
    public bool $withoutActionEvents = false;
    public static $displayInNavigation = false;

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
     * @param NovaRequest $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [
            ID::make()->sortable(),

            BelongsTo::make(__('credit.credit_account'), 'creditAccount', CreditAccount::class)
                ->withoutTrashed(),

            BelongsTo::make(__('credit.product'), 'product', \App\Nova\Inventory\Product::class)
                ->withoutTrashed()
                ->help(__('credit.select_product_credit')),

            Number::make(__('credit.quantity'), 'quantity')
                ->required()
                ->min(1)
                ->step(1)
                ->help(__('credit.number_units_credit'))
                ->displayUsing(function ($value) {
                    return number_format($value);
                })
                ->fillUsing(function ($request, $model, $attribute, $requestAttribute) {
                    $model->{$attribute} = $request->input($attribute);
                    $model->calculateSubtotal();
                }),

            Text::make(__('credit.product_details'), function () {
                $product = $this->product;
                if (!$product) {
                    return __('credit.no_product_selected');
                }

                $details = [];
                if ($product->sku) {
                    $details[] = __('credit.sku') . ": {$product->sku}";
                }
                if ($product->brand) {
                    $details[] = __('credit.brand') . ": {$product->brand}";
                }
                if (isset($product->qty)) {
                    $details[] = __('credit.available_qty') . ": " . number_format($product->qty);
                }

                return implode(' | ', $details);
            })
                ->onlyOnForms()
                ->asHtml(),

            Currency::make(__('credit.original_price'), function () {
                return $this->product ? $this->product->price : 0;
            })
                ->onlyOnForms()
                ->displayUsing(fn($value) => number_format($value, 2))
                ->help(__('credit.regular_price'))
                ->readonly(),

            Currency::make(__('credit.unit_price'), 'unit_price')
                ->required()
                ->min(0)
                ->step(0.01)
                ->help(__('credit.price_per_unit'))
                // ❷ Reactiva el campo cuando 'product' cambie
                ->dependsOn('product', function (Currency $field, NovaRequest $request, $formData) {
                    if (!$request->unit_price && isset($formData->product)) {
                        $product = Product::find($formData->product);
                        if ($product) {
                            $field->value = $product->price;   // aparece en la UI
                        }
                    }
                })
                ->displayUsing(fn($value) => number_format($value, 2))
                ->fillUsing(function ($request, $model, $attribute, $requestAttribute) {
                    // Respeta la edición del operador
                    $model->{$attribute} = $request->input($attribute);
                    $model->calculateSubtotal();
                }),

            Text::make(__('credit.price_difference'), function () {
                if (!$this->product || !$this->unit_price) {
                    return 'N/A';
                }

                $originalPrice = $this->product->price;
                $difference = $this->unit_price - $originalPrice;
                $percentDiff = $originalPrice > 0 ? ($difference / $originalPrice) * 100 : 0;

                if ($difference > 0) {
                    return '<span class="text-red-500">+' . number_format($difference, 2) . ' (' . number_format($percentDiff, 2) . '%)</span>';
                } elseif ($difference < 0) {
                    return '<span class="text-green-500">' . number_format($difference, 2) . ' (' . number_format($percentDiff, 2) . '%)</span>';
                } else {
                    return '<span>' . __('credit.no_difference') . '</span>';
                }
            })
                ->onlyOnForms()
                ->asHtml()
                ->help(__('credit.difference_price')),

            Currency::make(__('credit.subtotal'), 'subtotal')
                ->help(__('credit.total_quantity_price'))
                ->readonly()                                // que no sea editable
                ->displayUsing(fn($value) => number_format($value, 2))
                // ───────── Cambia en tiempo real ─────────
                ->dependsOn(['quantity', 'unit_price'], function (Currency $field, NovaRequest $request, $formData) {
                    $qty = (float)($formData->quantity ?? 0);
                    $price = (float)($formData->unit_price ?? 0);

                    // Mostrar con máximo dos decimales
                    $field->value = number_format($qty * $price, 2, '.', '');
                }),
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param NovaRequest $request
     * @return array
     */
    public function cards(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param NovaRequest $request
     * @return array
     */
    public function filters(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param NovaRequest $request
     * @return array
     */
    public function lenses(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param NovaRequest $request
     * @return array
     */
    public function actions(NovaRequest $request)
    {
        return [];
    }

    public static function label()
    {
        return __('credit.credit_account_products');
    }
}
