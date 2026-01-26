<?php

namespace App\Models\Services;

use App\Events\ServiceActive;
use App\Events\ServiceCreated;
use App\Events\ServiceSuspend;
use App\Events\ServiceUpdateStatus;
use App\Models\Customers\Address;
use App\Models\Customers\Customer;
use App\Models\Invoice\Invoice;
use App\Models\Router;
use App\Models\ServiceRule;
use App\Settings\GeneralProviderConfig;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Ispgo\NapManager\Models\NapPort;
use Ispgo\NapManager\Models\NapBox;

/**
 * Service is an Eloquent model that represents a service entity in the application.
 * It contains methods for managing relationships and business logic,
 * such as generating invoices, handling service actions, and modifying service status.
 */
class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'router_id', 'customer_id', 'internet_plan_id', 'service_ip', 'plan_id', 'username_router',
        'password_router', 'service_status', 'activation_date', 'deactivation_date',
        'bandwidth', 'mac_address', 'installation_date', 'service_notes', 'contract_id',
        'support_contact', 'service_location', 'service_type', 'static_ip', 'data_limit',
        'last_maintenance', 'billing_cycle', 'service_priority', 'sn','unu_longitude','unu_latitude',
        'assigned_technician', 'service_contract', 'created_by', 'updated_by',
    ];

    protected $casts = [
        'deactivation_date' => 'datetime',
        'activation_date' => 'datetime',
        'installation_date' => 'date',
        'last_maintenance' => 'date',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function getFullNameAttribute()
    {
        return trim($this->first_name . ' ' . $this->last_name);
    }

    public function router()
    {
        return $this->belongsTo(Router::class);
    }
    public function rules() { return $this->hasMany(ServiceRule::class); }

    public function billingNovedades()
    {
        return $this->hasMany(\App\Models\BillingNovedad::class);
    }

    public function address()
    {
        return $this->belongsTo(Address::class, 'service_location', 'id');
    }

    public function getFullServiceNameAttribute()
    {
        $full_name = $this->customer->full_name ? $this->customer->full_name : $this->getFullNameAttribute();
        return "{$this->service_ip} - {$full_name}";
    }

    public function getServiceNameAttribute()
    {
        $formattedName = strtolower(str_replace(' ', '_', $this->customer->first_name));
        return "{$this->id}_{$formattedName}";
    }


    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function napPort()
    {
        return $this->hasOne(NapPort::class, 'service_id');
    }

    public function napBox()
    {
        return $this->hasOneThrough(
            NapBox::class,
            NapPort::class,
            'service_id',   // Foreign key on NapPort table...
            'id',           // Foreign key on NapBox table (NapBox primary key referenced by NapPort.nap_box_id)
            'id',           // Local key on Service table
            'nap_box_id'    // Local key on NapPort table pointing to NapBox
        );
    }

    protected static function boot()
    {
        parent::boot();

        // Global Scope: Filter by user's router through customer
        static::addGlobalScope('router_filter', function (\Illuminate\Database\Eloquent\Builder $builder) {
            /** @var \App\Models\User|null $user */
            $user = \Illuminate\Support\Facades\Auth::user();

            // If not authenticated, no filtering
            if (!$user) {
                return;
            }

            // If user has no router assigned, show all data
            // Role permissions control what actions they can perform
            if (!$user->router_id) {
                return;
            }

            // Filter by user's assigned router_id (through customer or direct)
            $builder->where(function ($query) use ($user) {
                $query->whereHas('customer', function ($q) use ($user) {
                    $q->where('router_id', $user->router_id);
                })->orWhere('router_id', $user->router_id);
            });
        });

        static::creating(function ($model) {
            $model->created_by = Auth::id();
            $model->updated_by = Auth::id();
        });

        static::created(function ($model) {
            event(new ServiceCreated($model));

        });
        static::updating(function ($service) {
            if ($service->isDirty('service_status')) {
                $service->updated_by = Auth::id();
                event(new ServiceUpdateStatus($service));
            }
            event(new ServiceUpdateStatus($service));
        });
    }

    public function generateInvoice($notes = null): ?Invoice
    {
        if ($this->service_status == 'free') {
            // No generar factura para servicios con estado 'free'
            return null;
        }
        $price = $this->plan->monthly_price;

        $invoice = new Invoice();
        $invoice->service_id = $this->id;
        $invoice->customer_id = $this->customer_id;

        // Usar el usuario autenticado o el usuario por defecto
        $invoice->user_id = Auth::id() ?? GeneralProviderConfig::getDefaultUser();

        $invoice->subtotal = $price;
        $invoice->tax = 0;
        $invoice->total = $price;
        $invoice->amount = 0;
        $invoice->discount = 0;
        $invoice->outstanding_balance = $price;
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
        $action->action_date = $this->created_at;
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
        event(new ServiceSuspend($this));
    }

    public function activate()
    {
        $this->service_status = 'active';
        $this->save();
        event(new ServiceActive($this));
    }

    public static function getServicesWithUnpaidInvoices()
    {
        return self::where('service_status', '!=', 'free')
            ->whereHas('invoices', function ($query) {
                $query->where('status', 'unpaid');
            })
            ->get();
    }

    public static function getAllActiveServicesForInvoiceMonthly()
    {
        return self::where('service_status', '!=', 'free')->get();
    }

    /**
     * Find services by customer's name (first name or last name).
     *
     * @param string $str Customer's name to search for
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function findByCustomerName(string $str): \Illuminate\Database\Eloquent\Collection
    {
        return self::whereHas('customer', function ($query) use ($str) {
            $query->where('first_name', 'like', "%{$str}%")
                ->orWhere('last_name', 'like', "%{$str}%");
        })->get();
    }


    /**
     * Retrieve services associated with a specific customer ID.
     *
     * @param int $id The ID of the customer whose services are being retrieved.
     * @return \Illuminate\Support\Collection The collection of services associated with the customer.
     */
    public static function getServicesByCustomerID(int $id): \Illuminate\Support\Collection
    {
        return self::where('customer_id', $id)->get();
    }

}
