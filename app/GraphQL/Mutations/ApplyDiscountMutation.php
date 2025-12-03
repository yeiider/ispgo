<?php

namespace App\GraphQL\Mutations;

use App\Models\Invoice\Invoice;
use Illuminate\Support\Facades\Log;

class ApplyDiscountMutation
{
    public function resolve($_, array $args)
    {
        try {
            $invoice = Invoice::find($args['invoice_id']);

            if (!$invoice) {
                return [
                    'success' => false,
                    'message' => __('Invoice not found.'),
                ];
            }

            if ($invoice->status === Invoice::STATUS_PAID) {
                return [
                    'success' => false,
                    'message' => __('Cannot apply discount to a paid invoice.'),
                ];
            }

            $discount = $args['discount'];
            $isPercentage = $args['is_percentage'];
            $includeTax = $args['include_tax'];

            // Calculate discount amount
            if ($isPercentage) {
                $discountAmount = $invoice->total * ($discount / 100);
            } else {
                if ($discount > $invoice->total) {
                    return [
                        'success' => false,
                        'message' => __('Discount cannot be greater than the total amount of the invoice.'),
                    ];
                }
                $discountAmount = $discount;
            }

            // Apply discount
            if ($includeTax) {
                $invoice->applyDiscountWithTax($discountAmount);
            } else {
                $invoice->applyDiscountWithoutTax($discountAmount);
            }

            return [
                'success' => true,
                'message' => __('Discount applied successfully!'),
            ];

        } catch (\Exception $e) {
            Log::error('Error in ApplyDiscountMutation', [
                'invoice_id' => $args['invoice_id'] ?? null,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => __('Error applying discount: :message', ['message' => $e->getMessage()]),
            ];
        }
    }
}
