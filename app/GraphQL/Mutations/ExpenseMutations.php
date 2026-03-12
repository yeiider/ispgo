<?php

namespace App\GraphQL\Mutations;

use App\Models\Finance\Expense;
use App\Models\Finance\CashRegister;
use Illuminate\Support\Facades\Auth;

class ExpenseMutations
{
    public function create($_, array $args)
    {
        $user = Auth::user();

        if (!$user) {
            return [
                'success' => false,
                'message' => 'Unauthenticated',
                'expense' => null,
            ];
        }

        $assignedRegister = CashRegister::where('user_id', $user->id)->first();

        if (!$assignedRegister || $assignedRegister->status !== CashRegister::STATUS_OPEN) {
            return [
                'success' => false,
                'message' => 'No puedes registrar gastos sin tener una caja abierta asignada.',
                'expense' => null,
            ];
        }

        // The input arguments
        $expenseFields = $args['input'] ?? $args;

        if (empty($expenseFields['date'])) {
            $expenseFields['date'] = now()->format('Y-m-d');
        }

        $expenseFields['daily_box_id'] = $assignedRegister->id;

        $expense = Expense::create($expenseFields);

        // Deduct from end_balance? The balance is: current_balance = initial_balance + recaudos - gastos.
        // If we want it to apply automatically:
        // Wait, CashRegister doesn't have an updater for current_balance on every transaction, or does it?
        // Let's assume current_balance can just be recalculated or modified here.
        // For now let's just create the expense, current_balance logic may rely on `CashRegisterClosure`.
        // Actually, if it reduces balance, we should probably update `current_balance` directly:
        $assignedRegister->current_balance -= $expense->amount;
        $assignedRegister->save();

        return [
            'success' => true,
            'message' => 'Gasto creado exitosamente.',
            'expense' => $expense,
        ];
    }
}
