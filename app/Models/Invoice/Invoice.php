<?php

namespace App\Models\Invoice;


use App\Events\InvoiceIssued;
use App\Events\InvoicePaid;
use App\Events\InvoiceUpdateStatus;
use App\Helpers\QrCodeHelper;
use App\Helpers\Utils;
use App\Models\Customers\Customer;
use App\Models\InvoiceAdjustment;
use App\Models\InvoiceItem;
use App\Models\Router;
use App\Models\Services\Service;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Invoice extends Model
{
    use HasFactory;

    const STATUS_PAID = "paid";
    protected $fillable = [
        'service_id', 'customer_id', 'user_id', 'subtotal', 'tax', 'total', 'amount', 'outstanding_balance',
        'issue_date', 'due_date', 'full_name', 'status', 'payment_method', 'notes', 'created_by', 'updated_by', 'discount', 'payment_support', 'increment_id', 'additional_information', 'daily_box_id',
        'payment_link', 'expiration_date', 'customer_name', 'billing_period', 'state', 'amount_before_discounts', 'tax_total', 'void_total','router_id',
        // OnePay integration fields
        'onepay_charge_id', 'onepay_payment_link', 'onepay_status', 'onepay_metadata'
    ];

    protected $casts = [
        "due_date" => 'date',
        "issue_date" => 'date',
        "expiration_date" => 'date',
        "additional_information" => 'array',
        'quantity' => 'int',
        'onepay_metadata' => 'array',

    ];
    protected $appends = ['full_name', 'email_address', 'qr_image', 'issue__month_formatted', 'total_formatted', 'due_date_formatted', 'url_preview', 'url_pay', 'total_paid', 'credit_notes_total', 'real_outstanding_balance'];

    public function getFullNameAttribute()
    {
        return ucfirst("{$this->customer->first_name} {$this->customer->last_name}");
    }

    public function getInvoiceFullNameDescriptionsAttribute()
    {
        return $this->increment_id . ' - ' . ucfirst("{$this->customer->first_name} {$this->customer->last_name}");
    }

    public function getBillingPeriodStartAttribute(): ?Carbon
    {
        return $this->issue_date?->copy()->startOfMonth();
    }

    public function adjustments()
    {
        return $this->hasMany(InvoiceAdjustment::class);
    }

    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function getTotalWithAdjustmentsAttribute(): float
    {
        $base = $this->total; // o subtotal + taxes
        $delta = $this->adjustments()->sum('amount');
        return $base + $delta;
    }

    public function recalcTotals(): void
    {
        $adjustments = $this->adjustments()->get();

        $charges = $adjustments->where('kind', 'charge')->sum('amount');
        $discounts = $adjustments->where('kind', 'discount')->sum('amount');
        $taxes = $adjustments->where('kind', 'tax')->sum('amount');
        $voids = $adjustments->where('kind', 'void')->sum('amount');

        $subtotal = $charges + $discounts; // descuento ya viene negativo
        $total = $subtotal + $taxes + $voids;

        $this->subtotal = $subtotal;
        $this->discount = abs($discounts);
        $this->tax_total = $taxes;
        $this->void_total = $voids;
        $this->amount_before_discounts = $charges + $taxes;
        $this->total = $total;
        $this->outstanding_balance = max(0, $total);

        $this->save();
    }


    public function products()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function finalize(): void
    {
        $this->state = 'issued';
        $this->save();
        event(new InvoiceIssued($this));
    }


    public function getEmailAddressAttribute()
    {
        return $this->customer->email_address;
    }

    /**
     * Get the total amount paid through InvoicePayment records or direct payments.
     * Note: The 'amount' field contains the total paid (updated by both payment records and direct payments)
     */
    public function getTotalPaidAttribute(): float
    {
        return $this->amount ?? 0;
    }

    /**
     * Get the total amount of credit notes applied.
     */
    public function getCreditNotesTotalAttribute(): float
    {
        return $this->creditNotes()->sum('amount');
    }

    /**
     * Get the real outstanding balance considering payments and credit notes.
     * Formula: total - amount - credit_notes
     * Note: 'amount' field contains total paid (from both InvoicePayment records and direct payments)
     */
    public function getRealOutstandingBalanceAttribute(): float
    {
        return max(0, $this->total - $this->amount - $this->credit_notes_total);
    }

    /**
     * Check if the invoice is fully paid.
     */
    public function isFullyPaid(): bool
    {
        return $this->real_outstanding_balance <= 0;
    }


    public function creditNotes()
    {
        return $this->hasMany(CreditNote::class);
    }

    public function payments()
    {
        return $this->hasMany(InvoicePayment::class);
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

    public function router()
    {
        return $this->belongsTo(Router::class);
    }

    public static function findByDniOrInvoiceId($input)
    {
        return self::where(function ($query) use ($input) {
            $query->whereHas('customer', function ($query) use ($input) {
                $query->where('identity_document', $input);
            })->orWhere('increment_id', $input);
        })->where('status', 'unpaid')->orderBy('id', 'desc')->first();
    }

    public static function searchInvoice($input)
    {
        return self::where('status', 'unpaid')
            ->where(function ($query) use ($input) {
                $query->whereHas('customer', function ($query) use ($input) {
                    $query->where('identity_document', 'LIKE', "%{$input}%")
                        ->orWhere('first_name', 'LIKE', "%{$input}%");
                })
                ->orWhere('increment_id', 'LIKE', "%{$input}%");
            })
            ->orderBy('id', 'desc')
            ->get();
    }


    protected static function generateIncrementId()
    {
        $lastInvoice = self::orderBy('id', 'desc')->first();
        $lastId = $lastInvoice ? intval($lastInvoice->id) : 0;
        $incrementId = str_pad($lastId + 1, 10, '0', STR_PAD_LEFT);
        return $incrementId;
    }

    public function applyPayment($amount = null, $paymentMethod = "cash", array $additional = [], $notes = null, $dailyBoxId = null, $createPaymentRecord = false): void
    {
        $amount = $amount ?? $this->real_outstanding_balance;
        
        if ($this->status === 'paid') {
            throw new \Exception('La factura ya ha sido pagada');
        }
        
        if ($amount > $this->real_outstanding_balance) {
            throw new \Exception('El monto pagado no puede ser mayor que el saldo pendiente.');
        }

        // Only create InvoicePayment record for partial payments (when explicitly requested)
        if ($createPaymentRecord) {
            InvoicePayment::create([
                'invoice_id' => $this->id,
                'user_id' => Auth::id(),
                'amount' => $amount,
                'payment_date' => now(),
                'payment_method' => $paymentMethod,
                'notes' => $notes,
                'additional_information' => $additional,
            ]);
            
            // Update amount from sum of payments
            $this->amount = $this->payments()->sum('amount');
        } else {
            // For full payments or console commands: update amount directly
            $this->amount += $amount;
        }

        $this->outstanding_balance = $this->real_outstanding_balance;
        $this->payment_method = $paymentMethod;

        if ($this->isFullyPaid()) {
            $this->status = 'paid';
            $this->outstanding_balance = 0;
        } else if ($this->due_date < now() && $this->outstanding_balance > 0) {
            $this->status = 'unpaid';
        }
        
        if ($notes) {
            $this->notes = $notes;
        }
        
        $this->daily_box_id = $dailyBoxId;
        $this->additional_information = $additional;
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

        // Delete OnePay payment if exists
        if ($this->onepay_charge_id) {
            try {
                $onePayHandler = app(\App\Services\Payments\OnePay\OnePayHandler::class);
                $onePayHandler->deletePayment($this->onepay_charge_id);
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::warning('Error eliminando cobro OnePay al cancelar factura', [
                    'invoice_id' => $this->id,
                    'onepay_charge_id' => $this->onepay_charge_id,
                    'error' => $e->getMessage(),
                ]);
            }
        }
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

    protected static function boot(): void
    {
        parent::boot();

        // Global Scope: Filter by user's router
        static::addGlobalScope('router_filter', function (\Illuminate\Database\Eloquent\Builder $builder) {
            /** @var \App\Models\User|null $user */
            $user = Auth::user();
            
            // If not authenticated, no filtering
            if (!$user) {
                return;
            }

            // If user has no router assigned, show all data
            // Role permissions control what actions they can perform
            if (!$user->router_id) {
                return;
            }

            // Filter by user's assigned router_id (direct or through customer)
            $builder->where(function ($query) use ($user) {
                $query->where('router_id', $user->router_id)
                    ->orWhereHas('customer', function ($q) use ($user) {
                        $q->where('router_id', $user->router_id);
                    });
            });
        });

        static::creating(function ($model) {
            $model->created_by = Auth::id();
            $model->updated_by = Auth::id();
            $model->increment_id = self::generateIncrementId();
            if ($model->customer) {
                $model->customer_name = $model->customer->first_name . ' ' . $model->customer->last_name;
            }
            // event(new InvoiceCreatedBefore($model));
        });
        static::created(function ($model) {
            $model->load('customer');
            // event(new InvoiceCreated($model));
        });
        static::updating(function ($model) {
            $model->updated_by = Auth::id();
            if ($model->isDirty('status')) {
                //  event(new InvoiceUpdateStatus($model));
            }
        });

        static::deleting(function ($model) {
            // Delete OnePay payment if exists
            if ($model->onepay_charge_id) {
                try {
                    $onePayHandler = app(\App\Services\Payments\OnePay\OnePayHandler::class);
                    $onePayHandler->deletePayment($model->onepay_charge_id);
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\Log::warning('Error eliminando cobro OnePay al eliminar factura', [
                        'invoice_id' => $model->id,
                        'onepay_charge_id' => $model->onepay_charge_id,
                        'error' => $e->getMessage(),
                    ]);
                }
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

    /**
     * Generate QR Code for the increment ID.
     *
     * @return string Generated QR Code as a string.
     */
    public function getQrImageAttribute(): string
    {
        return QrCodeHelper::generateQrCode($this->increment_id);
    }


    /**
     * @return string
     */
    public function getIssueMonthFormattedAttribute(): string
    {
        return Utils::getMonthFormDate($this->issue_date);
    }


    /**
     * Get the total amount formatted as a string.
     *
     * @return string
     */
    public function getTotalFormattedAttribute(): string
    {
        return Utils::priceFormat($this->total, ['locale' => 'es', 'currency' => 'COP']);
    }


    /**
     * Get the formatted due date as a string.
     *
     * @return string
     */
    public function getDueDateFormattedAttribute(): string
    {
        return Utils::formatToDayAndMonth($this->due_date);
    }


    /**
     * Get the URL preview for the invoice.
     *
     * @return string
     */
    public function getUrlPreviewAttribute(): string
    {
        return route('preview.invoice', $this->increment_id);
    }

    /**
     * Get the payment URL for the invoice as a string.
     *
     * @return string
     */
    public function getUrlPayAttribute(): string
    {
        return route('checkout.index') . '?invoice=' . $this->increment_id;
    }


}
