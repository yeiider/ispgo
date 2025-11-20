<?php

namespace App\Models\Invoice;

use App\Models\Customers\Customer;
use App\Models\User;
use App\Models\Services\Service;
use App\Settings\InvoiceProviderConfig;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class PaymentPromise extends Model
{
    use HasFactory;

    protected $fillable = ['invoice_id', 'customer_id', 'user_id', 'amount', 'promise_date', 'notes','status'];

    protected $casts = [
        'promise_date' => 'date'
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->user_id) && Auth::check()) {
                $model->user_id = Auth::id();
            }
        });

        static::saved(function (PaymentPromise $promise) {
            static::syncServiceState($promise);
        });

        static::deleted(function (PaymentPromise $promise) {
            static::evaluateServiceAfterDeletion($promise);
        });
    
        static::addGlobalScope('orderByStatus', function (Builder $builder) {
            $builder->orderByRaw("FIELD(status, 'pending', 'fulfilled', 'cancelled')");
        });
    }

    protected static function syncServiceState(PaymentPromise $promise): void
    {
        if (!static::isServiceManagementEnabled()) {
            return;
        }

        if ($promise->status !== 'pending') {
            return;
        }

        $promise->loadMissing('invoice.service', 'invoice.customer');

        $invoice = $promise->invoice;

        if (!$invoice) {
            return;
        }

        $service = $invoice->service;

        if (!$service) {
            return;
        }

        $today = now()->startOfDay();
        $promiseDate = optional($promise->promise_date)?->copy()->startOfDay();

        if ($promiseDate && $promiseDate->lt($today)) {
            if ($service->service_status !== 'suspended') {
                $service->suspend();
            }
            return;
        }

        if (!$promiseDate || $promiseDate->lt($today)) {
            return;
        }

        if ($service->service_status === 'active') {
            return;
        }

        if (static::hasBlockingUnpaidInvoices($invoice, $service)) {
            return;
        }

        $service->activate();
    }

    protected static function evaluateServiceAfterDeletion(PaymentPromise $promise): void
    {
        if (!static::isServiceManagementEnabled()) {
            return;
        }

        $promise->loadMissing('invoice.service');

        $invoice = $promise->invoice;

        if (!$invoice) {
            return;
        }

        $service = $invoice->service;

        if (!$service) {
            return;
        }

        $today = now()->startOfDay();

        $stillHasPromise = $invoice->paymentPromises()
            ->where('status', 'pending')
            ->whereDate('promise_date', '>=', $today)
            ->exists();

        if ($stillHasPromise) {
            return;
        }

        if ($invoice->due_date && $invoice->due_date->lt($today) && $invoice->outstanding_balance > 0) {
            if ($service->service_status !== 'suspended') {
                $service->suspend();
            }
        }
    }

    protected static function hasBlockingUnpaidInvoices(Invoice $referenceInvoice, Service $serviceContext): bool
    {
        $today = now()->startOfDay();

        $query = Invoice::query()
            ->where('customer_id', $referenceInvoice->customer_id)
            ->where('id', '!=', $referenceInvoice->id)
            ->where('outstanding_balance', '>', 0)
            ->whereIn('status', ['unpaid', 'overdue'])
            ->whereDoesntHave('paymentPromises', function ($query) use ($today) {
                $query->where('status', 'pending')
                    ->whereDate('promise_date', '>=', $today);
            });

        if ($serviceContext->getKey()) {
            $query->where('service_id', $serviceContext->getKey());
        } else {
            $query->whereNull('service_id');
        }

        return $query->exists();
    }

    protected static function isServiceManagementEnabled(): bool
    {
        $configValue = InvoiceProviderConfig::enableServiceByPaymentPromise();

        if ($configValue === null) {
            return true;
        }

        $booleanValue = filter_var($configValue, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);

        return $booleanValue !== false;
    }
}
