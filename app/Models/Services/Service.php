<?php

namespace App\Models\Services;

use App\Events\ServiceUpdateStatus;
use App\Models\Customers\Address;
use App\Models\Customers\Customer;
use App\Models\Invoice\Invoice;
use App\Models\Router;
use App\Settings\GeneralProviderConfig;
use Carbon\Carbon;
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
        return $this->belongsTo(Address::class, 'service_location', 'id');
    }

    public function getFullServiceNameAttribute()
    {
        return "{$this->service_ip} - {$this->customer->full_name}";
    }

    public function plan()
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

    public function generateInvoice($notes = null): Invoice
    {
        $price = $this->plan->monthly_price;
        $tax = $price * 0.19;
        $total = $price + $tax;

        $invoice = new Invoice();
        $invoice->service_id = $this->id;
        $invoice->customer_id = $this->customer_id;

        // Usar el usuario autenticado o el usuario por defecto
        $invoice->user_id = Auth::id() ?? GeneralProviderConfig::getDefaultUser();

        $invoice->subtotal = $price;
        $invoice->tax = $tax;
        $invoice->total = $total;
        $invoice->amount = 0;
        $invoice->discount = 0;
        $invoice->outstanding_balance = $total;
        $invoice->issue_date = now();

        // Obtener el día de vencimiento configurado
        $dueDate = GeneralProviderConfig::getPaymentDueDate();
        $currentMonth = now()->month;
        $currentYear = now()->year;

        // Si la fecha de vencimiento es menor que la fecha actual, se pasa al siguiente mes
        if ($dueDate < now()->day) {
            $dueMonth = ($currentMonth == 12) ? 1 : $currentMonth + 1;
            $dueYear = ($currentMonth == 12) ? $currentYear + 1 : $currentYear;
        } else {
            $dueMonth = $currentMonth;
            $dueYear = $currentYear;
        }

        $invoice->due_date = Carbon::create($dueYear, $dueMonth, $dueDate, 0, 0, 0);
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

    public static function getAllActiveServicesForInvoiceMonthly()
    {
        return self::where('service_status', '!=', 'free')->get();
    }
}
