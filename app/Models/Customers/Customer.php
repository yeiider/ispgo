<?php

namespace App\Models\Customers;

use App\Events\CustomerCreated;
use App\Events\CustomerStatusUpdated;
use App\Models\Contract;
use App\Models\Invoice\Invoice;
use App\Models\Router;
use App\Models\Services\Service;
use App\Settings\GeneralProviderConfig;
use Carbon\Carbon;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Customer extends Authenticatable implements MustVerifyEmail
{
    use HasFactory;
    use Notifiable;

    protected $casts = [
        'date_of_birth' => 'date',
        'email_verified_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];
    protected $fillable = [
        'first_name',
        'last_name',
        'date_of_birth',
        'phone_number',
        'email_address',
        'document_type',
        'identity_document',
        'customer_status',
        'additional_notes',
        'created_by',
        'updated_by',
        'password',
        'onepay_customer_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token'
    ];


    public function openDraftInvoice(string $period): Invoice
    {
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
        $defaultUser = Auth::id() ?? GeneralProviderConfig::getDefaultUser();
        $dueDate = Carbon::create($dueYear, $dueMonth, $dueDate);
        return Invoice::create(
            [
                'billing_period' => $period,
                'state' => 'draft',
                'subtotal' => 0,
                'customer_id' => $this->id,
                'tax' => 0,
                'amount' => 0,
                'outstanding_balance' => 0,
                'issue_date' => now(),
                'due_date' => $dueDate,
                'total' => 0,
                'status' => 'unpaid',
                'user_id' => $defaultUser,
            ]
        );
    }

    public function activeServices()
    {
        return $this->services()->whereNotIn('service_status', ['free', 'inactive']);
    }

    public function addresses()
    {
        return $this->hasMany(Address::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function services()
    {
        return $this->hasMany(Service::class);
    }

    public function contracts()
    {
        return $this->hasMany(Contract::class);
    }

    public function taxDetails()
    {
        return $this->hasOne(TaxDetail::class);
    }

    public function getFullNameAttribute()
    {
        return ucwords("{$this->first_name} {$this->last_name}");
    }

    public function setFirstNameAttribute($value)
    {
        $this->attributes['first_name'] = strtolower($value);
    }

    public function setLastNameAttribute($value)
    {
        $this->attributes['last_name'] = strtolower($value);
    }

    protected static function booted()
    {

        static::created(function ($customer) {

            $customer->created_by = Auth::id();
            $customer->updated_by = Auth::id();
            event(new CustomerCreated($customer));
        });
        static::updating(function ($customer) {
            if ($customer->isDirty('customer_status')) {
                $customer->updated_by = Auth::id();
                event(new CustomerStatusUpdated($customer));
            }
        });
    }

    public function scopeActive($query)
    {
        return $query->where('customer_status', 'active');
        //how use $activeCustomers = Customer::active()->get();
    }

    public static function findByIdentityDocument($identityDocument)
    {
        return self::where('identity_document', $identityDocument)->first();
    }

    public static function searchCustomersWithInvoices($input)
    {
        return self::where(function ($query) use ($input) {
            $query->where('identity_document', 'LIKE', "%{$input}%")
                ->orWhere('first_name', 'LIKE', "%{$input}%");
        })
            ->with(['invoices' => function ($query) {
                $query->where('status', 'unpaid'); // Filtrar solo facturas no pagadas
            }])
            ->get();
    }


    public function getLastInvoice()
    {
        return $this->invoices()->orderBy('created_at', 'desc')->first();
    }

    public function router()
    {
        return $this->belongsTo(Router::class);
    }
}
