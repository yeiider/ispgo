<?php
namespace App\Nova\Repeaters;

use App\Models\Inventory\Product;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Repeater\Repeatable;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Http\Requests\NovaRequest;

class ProductLineItem extends Repeatable
{
    public function fields(NovaRequest $request): array
    {
        return [
            ID::hidden('uuid'),
            /* ─────────── Producto ─────────── */
            Select::make('Producto', 'product_id')
                ->options(
                    Product::query()
                        ->where('status', 1)
                        ->orderBy('name')
                        ->pluck('name', 'id')
                        ->toArray()
                )
                ->searchable()
                ->displayUsingLabels()
                ->rules('required'),

            /* ─────────── Cantidad ─────────── */
            Number::make('Cantidad', 'qty')
                ->min(1)->step(1)
                ->rules('required','integer','min:1'),

            /* ─────────── Precio Unitario ─────────── */
            Currency::make('Precio Unitario', 'unit_price')
                ->currency('COP')
                ->rules('numeric','min:0')
                ->default(0)
                ->help(__("Si desea que tome el precio de venta del producto, deje este campo en 0.")),
        ];
    }
}
