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

        $assignedRegister = CashRegister::where('user_id', $user->id)
            ->latest()
            ->first();

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
            $expenseFields['date'] = now()->toDateTimeString();
        }

        $expenseFields['daily_box_id'] = $assignedRegister->id;

        $expense = Expense::create($expenseFields);

        return [
            'success' => true,
            'message' => 'Gasto creado exitosamente.',
            'expense' => $expense,
        ];
    }
}
