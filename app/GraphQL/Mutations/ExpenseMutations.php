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
        $expenseFields['user_id'] = $user->id;

        $expense = Expense::create($expenseFields);

        return [
            'success' => true,
            'message' => 'Gasto creado exitosamente.',
            'expense' => $expense,
        ];
    }

    public function update($_, array $args)
    {
        $user = Auth::user();

        if (!$user) {
            return [
                'success' => false,
                'message' => 'No autenticado.',
                'expense' => null,
            ];
        }

        $id = $args['id'] ?? null;
        $expense = Expense::find($id);

        if (!$expense) {
            return [
                'success' => false,
                'message' => 'Gasto no encontrado.',
                'expense' => null,
            ];
        }

        $input = $args['input'] ?? $args;
        unset($input['id']);

        $expense->update(array_filter($input, function ($val) {
            return !is_null($val);
        }));

        return [
            'success' => true,
            'message' => 'Gasto actualizado exitosamente.',
            'expense' => $expense->fresh(['expenseCategory', 'supplier', 'user']),
        ];
    }

    public function delete($_, array $args)
    {
        $user = Auth::user();

        if (!$user) {
            return [
                'success' => false,
                'message' => 'No autenticado.',
            ];
        }

        $id = $args['id'] ?? null;
        $expense = Expense::find($id);

        if (!$expense) {
            return [
                'success' => false,
                'message' => 'Gasto no encontrado.',
            ];
        }

        $expense->delete();

        return [
            'success' => true,
            'message' => 'Gasto eliminado exitosamente.',
        ];
    }
}
