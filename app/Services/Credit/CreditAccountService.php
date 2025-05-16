<?php

namespace App\Services\Credit;

use App\Events\CreditOpened;
use App\Models\Credit\CreditAccount;
use App\Models\Credit\CreditAccountProduct;
use App\Models\Customers\Customer;
use App\Models\Inventory\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class CreditAccountService
{
    protected $installmentScheduler;

    public function __construct(InstallmentScheduler $installmentScheduler)
    {
        $this->installmentScheduler = $installmentScheduler;
    }

    /**
     * Open a new credit account for a customer.
     *
     * @param array $data
     * @return CreditAccount
     * @throws ValidationException
     */
    public function open(array $data): CreditAccount
    {
        // Validate input data
        $validator = Validator::make($data, [
            'customer_id' => 'required|exists:customers,id',
            'interest_rate' => 'required|numeric|min:0|max:100',
            'grace_period_days' => 'required|integer|min:0',
            'term_months' => 'required|integer|min:1',
            'products' => 'required|array|min:1',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        // Check if customer has overdue credits
        $customer = Customer::findOrFail($data['customer_id']);
        if ($this->customerHasOverdueCredits($customer)) {
            throw ValidationException::withMessages([
                'customer_id' => 'Customer has overdue credits and cannot open a new credit account.',
            ]);
        }

        // Calculate principal amount based on products
        $principal = 0;
        $products = [];

        foreach ($data['products'] as $productData) {
            $product = Product::findOrFail($productData['product_id']);
            $quantity = $productData['quantity'];
            $unitPrice = $product->price;
            $subtotal = $quantity * $unitPrice;

            $principal += $subtotal;

            $products[] = [
                'product_id' => $product->id,
                'quantity' => $quantity,
                'unit_price' => $unitPrice,
                'subtotal' => $subtotal,
            ];
        }

        // Create credit account with transaction to ensure data integrity
        return DB::transaction(function () use ($data, $principal, $products, $customer) {
            // Create credit account
            $creditAccount = CreditAccount::create([
                'customer_id' => $customer->id,
                'principal' => $principal,
                'interest_rate' => $data['interest_rate'],
                'grace_period_days' => $data['grace_period_days'],
                'status' => 'active',
            ]);

            // Attach products to credit account
            foreach ($products as $productData) {
                $creditAccount->products()->attach($productData['product_id'], [
                    'quantity' => $productData['quantity'],
                    'unit_price' => $productData['unit_price'],
                    'subtotal' => $productData['subtotal'],
                ]);
            }

            // Dispatch event to generate installments
            event(new CreditOpened($creditAccount, $data['term_months']));

            return $creditAccount;
        });
    }

    /**
     * Check if a customer has any overdue credit accounts.
     *
     * @param Customer $customer
     * @return bool
     */
    protected function customerHasOverdueCredits(Customer $customer): bool
    {
        return $customer->creditAccounts()->where('status', 'overdue')->exists();
    }

    /**
     * Mark a credit account as in grace period.
     *
     * @param CreditAccount $creditAccount
     * @param int $gracePeriodDays
     * @return CreditAccount
     */
    public function grantGracePeriod(CreditAccount $creditAccount, int $gracePeriodDays): CreditAccount
    {
        $creditAccount->grace_period_days = $gracePeriodDays;
        $creditAccount->status = 'in_grace';
        $creditAccount->save();

        return $creditAccount;
    }

    /**
     * Mark a credit account as closed.
     *
     * @param CreditAccount $creditAccount
     * @return CreditAccount
     */
    public function close(CreditAccount $creditAccount): CreditAccount
    {
        $creditAccount->status = 'closed';
        $creditAccount->save();

        return $creditAccount;
    }

    /**
     * Get a summary of a credit account.
     *
     * @param CreditAccount $creditAccount
     * @return array
     */
    public function getSummary(CreditAccount $creditAccount): array
    {
        return [
            'id' => $creditAccount->id,
            'customer' => $creditAccount->customer->full_name,
            'principal' => $creditAccount->principal,
            'interest_rate' => $creditAccount->interest_rate,
            'total_paid' => $creditAccount->total_paid,
            'remaining_balance' => $creditAccount->remaining_balance,
            'payment_progress' => $creditAccount->payment_progress,
            'status' => $creditAccount->status,
            'next_due_installment' => $creditAccount->next_due_installment ? [
                'due_date' => $creditAccount->next_due_installment->due_date->format('Y-m-d'),
                'amount_due' => $creditAccount->next_due_installment->amount_due,
            ] : null,
            'products' => $creditAccount->products->map(function ($product) {
                return [
                    'name' => $product->name,
                    'quantity' => $product->pivot->quantity,
                    'unit_price' => $product->pivot->unit_price,
                    'subtotal' => $product->pivot->subtotal,
                ];
            }),
        ];
    }
}
