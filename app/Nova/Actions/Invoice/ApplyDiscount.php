<?php

namespace App\Nova\Actions\Invoice\Invoice;

use App\Models\Invoice\Invoice;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Http\Requests\NovaRequest;

class ApplyDiscount extends Action
{
    use InteractsWithQueue, Queueable;

    /**
     * Perform the action on the given models.
     *
     * @param  \Laravel\Nova\Fields\ActionFields  $fields
     * @param  \Illuminate\Support\Collection  $models
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        foreach ($models as $invoice) {
            /** @var Invoice $invoice */
            // Validar que la factura no esté en estado 'paid'
            if ($invoice->status === Invoice::STATUS_PAID) {
                return Action::danger("Cannot apply discount to a paid invoice.");
            }

            $discount = $fields->discount;
            $isPercentage = $fields->is_percentage;
            $includeTax = $fields->include_tax;

            // Calcular el monto del descuento
            if ($isPercentage) {
                $discountAmount = $invoice->total * ($discount / 100);
            } else {
                if ($discount > $invoice->total) {
                    return Action::danger("Discount cannot be greater than the total amount of the invoice.");
                }
                $discountAmount = $discount;
            }

            // Aplicar el descuento
            if ($includeTax) {
                $invoice->applyDiscountWithTax($discountAmount);
            } else {
                $invoice->applyDiscountWithoutTax($discountAmount);
            }
        }

        return Action::message('Discount applied successfully!');
    }

    /**
     * Get the fields available on the action.
     *
     * @param NovaRequest $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [
            Number::make('Discount')
                ->rules('required', 'numeric', 'min:0'),

            Boolean::make('Is Percentage')
                ->rules('required', 'boolean'),

            Boolean::make('Include Tax')
                ->rules('required', 'boolean'),
        ];
    }
}
