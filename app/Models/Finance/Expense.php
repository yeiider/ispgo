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
}
