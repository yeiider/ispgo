<?php

namespace App\Models\Finance;

use App\Models\Inventory\Supplier;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = [
        'description', 'amount', 'date', 'payment_method', 'expense_category_id', 'daily_box_id', 'supplier_id'
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function expenseCategory()
    {
        return $this->belongsTo(ExpenseCategory::class);
    }

    public function dailyBox()
    {
        return $this->belongsTo(\App\Models\Finance\CashRegister::class, 'daily_box_id');
    }

    protected static function boot(): void
    {
        parent::boot();

        // Al crear un gasto, ya lo estamos restando en la mutación, 
        // pero por seguridad si se crea por otros medios (Nova), lo gestionamos aquí.
        static::created(function ($expense) {
            if ($expense->daily_box_id && $expense->payment_method === 'cash') {
                \App\Models\Finance\CashRegister::where('id', $expense->daily_box_id)->decrement('current_balance', $expense->amount);
            }
        });

        static::updated(function ($expense) {
            if ($expense->wasChanged(['amount', 'payment_method', 'daily_box_id'])) {
                $originalAmount = $expense->getOriginal('amount');
                $originalMethod = $expense->getOriginal('payment_method');
                $originalBoxId = $expense->getOriginal('daily_box_id');

                // Revertir el original si era efectivo
                if ($originalBoxId && $originalMethod === 'cash') {
                    \App\Models\Finance\CashRegister::where('id', $originalBoxId)->increment('current_balance', $originalAmount);
                }

                // Aplicar el nuevo si es efectivo
                if ($expense->daily_box_id && $expense->payment_method === 'cash') {
                    \App\Models\Finance\CashRegister::where('id', $expense->daily_box_id)->decrement('current_balance', $expense->amount);
                }
            }
        });

        static::deleted(function ($expense) {
            if ($expense->daily_box_id && $expense->payment_method === 'cash') {
                \App\Models\Finance\CashRegister::where('id', $expense->daily_box_id)->increment('current_balance', $expense->amount);
            }
        });
    }
}
