<?php

namespace App\Models\Invoice;

use App\Events\InvoiceCreated;
use App\Events\InvoicePaid;
use App\Events\InvoiceUpdateStatus;
use App\Models\Customers\Customer;
use App\Models\Services\Service;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Invoice extends Model
{
    use HasFactory;

    const STATUS_PAID = "paid";
    protected $fillable = [
        'service_id', 'customer_id', 'user_id', 'subtotal', 'tax', 'total', 'amount', 'outstanding_balance',
        'issue_date', 'due_date', 'status', 'payment_method', 'notes', 'created_by', 'updated_by', 'discount', 'payment_support'
    ];

    protected $casts = [
        "due_date" => "date",
        "issue_date" => "date"
    ];

    public function getFullNameAttribute()
    {
        return ucfirst("{$this->customer->first_name} {$this->customer->last_name}");
    }

    public function getEmailAddressAttribute()
    {
        return $this->customer->email_address;
    }

    public function creditNotes()
    {
        return $this->hasMany(CreditNote::class);
    }

    public function paymentPromises()
    {
        return $this->hasMany(PaymentPromise::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function applyPayment($amount = null, $paymentMethod = "cash"): void
    {
        $this->amount += $amount ?? $this->total;
        $this->outstanding_balance = $this->total - $this->amount;
        $this->payment_method = $paymentMethod;

        if ($this->outstanding_balance <= 0) {
            $this->status = 'paid';
            $this->outstanding_balance = 0;
        } else if ($this->due_date < now() && $this->outstanding_balance > 0) {
            $this->status = 'overdue';
        }

        $this->save();
        event(new InvoicePaid($this));
    }

    public function createPromisePayment($date, $notes = null)
    {
        return PaymentPromise::create([
            'invoice_id' => $this->id,
            'customer_id' => $this->customer->id,
            'user_id' => Auth::id(),
            'amount' => $this->total,
            'promise_date' => $date,
            'notes' => $notes,
        ]);
    }

    public function canceled()
    {
        $this->status = 'canceled';
        $this->save();
    }

    public function applyDiscountWithoutTax(float $discount)
    {
        $this->discount = $discount;
        $subtotal = $this->subtotal - $discount;
        $tax = $subtotal * 0.19;
        $total = $subtotal + $tax;

        $this->subtotal = $subtotal;
        $this->tax = $tax;
        $this->total = $total;
        $this->outstanding_balance = $total - $this->amount;
        $this->save();
    }

    public function applyDiscountWithTax(float $discount)
    {
        $this->discount = $discount;
        $total = $this->total - $discount;
        $subtotal = $total / 1.19;
        $tax = $total - $subtotal;

        $this->subtotal = $subtotal;
        $this->tax = $tax;
        $this->total = $total;
        $this->outstanding_balance = $total - $this->amount;
        $this->save();
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->created_by = Auth::id();
            $model->updated_by = Auth::id();
            event(new InvoiceCreated($model));
        });

        static::updating(function ($model) {
            $model->updated_by = Auth::id();
            if ($model->isDirty('status')) {
                event(new InvoiceUpdateStatus($model));
            }
        });
    }

    public static function calculateDailyBalances($date): void
    {
        $invoices = self::whereDate('issue_date', $date)
            ->where('status', 'paid')->get();
        if ($invoices->count()) {
            $totalInvoices = $invoices->count();
            $paidInvoices = $invoices->where('status', 'paid')->count();
            $totalSubtotal = $invoices->sum('subtotal');
            $totalTax = $invoices->sum('tax');
            $totalAmount = $invoices->sum('amount');
            $totalDiscount = $invoices->sum('discount');
            $totalOutstandingBalance = $invoices->sum('outstanding_balance');
            $totalRevenue = $invoices->sum('total');

            DailyInvoiceBalance::create([
                'date' => $date,
                'total_invoices' => $totalInvoices,
                'paid_invoices' => $paidInvoices,
                'total_subtotal' => $totalSubtotal,
                'total_tax' => $totalTax,
                'total_amount' => $totalAmount,
                'total_discount' => $totalDiscount,
                'total_outstanding_balance' => $totalOutstandingBalance,
                'total_revenue' => $totalRevenue,
            ]);
        }
    }
}
