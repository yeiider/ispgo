<?php

namespace App\Models\Services;

use App\Events\ServiceUpdateStatus;
use App\Models\Customers\Address;
use App\Models\Customers\Customer;
use App\Models\Invoice\Invoice;
use App\Models\Router;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'router_id', 'customer_id', 'internet_plan_id', 'service_ip', 'username_router',
        'password_router', 'service_status', 'activation_date', 'deactivation_date',
        'bandwidth', 'mac_address', 'installation_date', 'service_notes', 'contract_id',
        'support_contact', 'service_location', 'service_type', 'static_ip', 'data_limit',
        'last_maintenance', 'billing_cycle', 'service_priority',
        'assigned_technician', 'service_contract', 'created_by', 'updated_by',

    ];

    protected $casts = [
        'deactivation_date' => 'date',
        'activation_date' => 'date',
        'installation_date' => 'date',
        'last_maintenance' => 'date',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function router()
    {
        return $this->belongsTo(Router::class);
    }

    public function address()
    {
        return $this->belongsTo(Address::class,'service_location','id');
    }

    public function getFullServiceNameAttribute()
    {
        return "{$this->service_ip} - {$this->customer->full_name}";
    }

    public function Plan()
    {
        return $this->belongsTo(Plan::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->created_by = Auth::id();
            $model->updated_by = Auth::id();
        });

        static::updating(function ($service) {
            if ($service->isDirty('service_status')) {
                $service->updated_by = Auth::id();
                event(new ServiceUpdateStatus($service));
            }
        });

    }

    public function generateInvoice($notes = null): \App\Models\Invoice\Invoice
    {
        $price = $this->plan->monthly_price;
        $tax = $price * 0.19;
        $total = $price + $tax;

        $invoice = new Invoice();
        $invoice->service_id = $this->id;
        $invoice->customer_id = $this->customer_id;
        $invoice->user_id = Auth::id(); // Asumiendo que el usuario autenticado está generando la factura
        $invoice->subtotal = $price;
        $invoice->tax = $tax;
        $invoice->total = $total;
        $invoice->amount = 0;
        $invoice->discount = 0;
        $invoice->outstanding_balance = $total;
        $invoice->issue_date = now();
        $invoice->due_date = now()->addDays(5);
        $invoice->status = 'unpaid';
        $invoice->payment_method = null;
        $invoice->notes = $notes;
        $invoice->save();

        return $invoice;
    }

    public function installations()
    {
        return $this->hasMany(ServiceAction::class);
    }

    public function createInstallation($technician_id = null, $action_date = null, $notes = null): ServiceAction
    {
        $action = new ServiceAction();
        $action->service_id = $this->id;
        $action->action_type = 'installation';
        $action->action_date = $action_date ?? now();
        $action->user_id = $technician_id;
        $action->action_notes = $notes;
        $action->status = 'pending';
        $action->save();

        return $action;
    }


    public function createUninstallation($technician_id = null, $action_date = null, $notes = null): ServiceAction
    {
        $action = new ServiceAction();
        $action->service_id = $this->id;
        $action->action_type = 'uninstallation';
        $action->action_date = $action_date ?? now();
        $action->user_id = $technician_id;
        $action->action_notes = $notes;
        $action->status = 'pending';
        $action->save();

        return $action;
    }

    public function suspend()
    {
        $this->service_status = 'suspended';
        $this->save();
    }

    public function activate()
    {
        $this->service_status = 'active';
        $this->save();
    }

}
