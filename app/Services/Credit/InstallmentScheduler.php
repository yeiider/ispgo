<?php

namespace App\Services\Credit;

use App\Models\Credit\CreditAccount;
use App\Models\Credit\CreditInstallment;
use Carbon\Carbon;

class InstallmentScheduler
{
    /**
     * Generate installments for a credit account using the French amortization system (fixed payments).
     *
     * @param CreditAccount $creditAccount
     * @param int $termMonths
     * @return array
     */
    public function generateInstallments(CreditAccount $creditAccount, int $termMonths): array
    {
        $principal = $creditAccount->principal;
        $annualInterestRate = $creditAccount->interest_rate;
        $monthlyInterestRate = $annualInterestRate / 12 / 100; // Convert annual rate to monthly decimal

        // Calculate fixed monthly payment using the French amortization formula
        // PMT = P * r * (1 + r)^n / ((1 + r)^n - 1)
        $fixedMonthlyPayment = 0;
        if ($monthlyInterestRate > 0) {
            $fixedMonthlyPayment = $principal * $monthlyInterestRate * pow(1 + $monthlyInterestRate, $termMonths)
                / (pow(1 + $monthlyInterestRate, $termMonths) - 1);
        } else {
            // If interest rate is 0, simply divide principal by term
            $fixedMonthlyPayment = $principal / $termMonths;
        }

        // Round to 2 decimal places
        $fixedMonthlyPayment = round($fixedMonthlyPayment, 2);

        $installments = [];
        $remainingPrincipal = $principal;
        $startDate = Carbon::now()->addDays($creditAccount->grace_period_days);

        for ($i = 0; $i < $termMonths; $i++) {
            // Calculate interest portion for this period
            $interestPortion = round($remainingPrincipal * $monthlyInterestRate, 2);

            // Calculate principal portion for this period
            $principalPortion = $fixedMonthlyPayment - $interestPortion;

            // Adjust for the last payment to account for rounding errors
            if ($i === $termMonths - 1) {
                $principalPortion = $remainingPrincipal;
                $fixedMonthlyPayment = $principalPortion + $interestPortion;
            }

            // Calculate due date (one month after the previous due date)
            $dueDate = $startDate->copy()->addMonths($i);

            // Create installment
            $installment = new CreditInstallment([
                'credit_account_id' => $creditAccount->id,
                'due_date' => $dueDate,
                'amount_due' => $fixedMonthlyPayment,
                'interest_portion' => $interestPortion,
                'principal_portion' => $principalPortion,
                'status' => 'pending',
            ]);

            $installments[] = $installment;

            // Update remaining principal for next iteration
            $remainingPrincipal -= $principalPortion;
        }

        return $installments;
    }

    /**
     * Save generated installments to the database.
     *
     * @param CreditAccount $creditAccount
     * @param int $termMonths
     * @return array
     */
    public function createInstallments(CreditAccount $creditAccount, int $termMonths): array
    {
        $installments = $this->generateInstallments($creditAccount, $termMonths);

        foreach ($installments as $installment) {
            $installment->save();
        }

        return $installments;
    }

    /**
     * Calculate the total interest to be paid over the life of the loan.
     *
     * @param CreditAccount $creditAccount
     * @param int $termMonths
     * @return float
     */
    public function calculateTotalInterest(CreditAccount $creditAccount, int $termMonths): float
    {
        $installments = $this->generateInstallments($creditAccount, $termMonths);

        $totalInterest = 0;
        foreach ($installments as $installment) {
            $totalInterest += $installment->interest_portion;
        }

        return round($totalInterest, 2);
    }

    /**
     * Calculate the total amount to be paid (principal + interest).
     *
     * @param CreditAccount $creditAccount
     * @param int $termMonths
     * @return float
     */
    public function calculateTotalAmount(CreditAccount $creditAccount, int $termMonths): float
    {
        return $creditAccount->principal + $this->calculateTotalInterest($creditAccount, $termMonths);
    }

    /**
     * Get a preview of the installment schedule without saving to the database.
     *
     * @param float $principal
     * @param float $interestRate
     * @param int $termMonths
     * @param int $gracePeriodDays
     * @return array
     */
    public function previewInstallments(float $principal, float $interestRate, int $termMonths, int $gracePeriodDays = 0): array
    {
        // Create a temporary credit account object
        $creditAccount = new CreditAccount([
            'principal' => $principal,
            'interest_rate' => $interestRate,
            'grace_period_days' => $gracePeriodDays,
        ]);

        $installments = $this->generateInstallments($creditAccount, $termMonths);

        // Format for preview
        return [
            'principal' => $principal,
            'interest_rate' => $interestRate,
            'term_months' => $termMonths,
            'total_interest' => $this->calculateTotalInterest($creditAccount, $termMonths),
            'total_amount' => $this->calculateTotalAmount($creditAccount, $termMonths),
            'monthly_payment' => $installments[0]->amount_due,
            'installments' => collect($installments)->map(function ($installment) {
                return [
                    'due_date' => $installment->due_date->format('Y-m-d'),
                    'amount_due' => $installment->amount_due,
                    'interest_portion' => $installment->interest_portion,
                    'principal_portion' => $installment->principal_portion,
                ];
            })->toArray(),
        ];
    }
}
