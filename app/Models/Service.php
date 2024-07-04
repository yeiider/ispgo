<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'router_id', 'customer_id', 'internet_plan_id', 'service_ip', 'username_router',
        'password_router', 'service_status', 'activation_date', 'deactivation_date',
        'bandwidth', 'mac_address', 'installation_date', 'service_notes', 'contract_id',
        'support_contact', 'service_location', 'service_type', 'static_ip', 'data_limit',
        'last_maintenance', 'billing_cycle', 'monthly_fee', 'overage_fee', 'service_priority',
        'assigned_technician', 'service_contract'
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'activation_date' => 'date',
        'deactivation_date' => 'date'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function router()
    {
        return $this->belongsTo(Router::class);
    }

    public function internetPlan()
    {
        return $this->belongsTo(InternetPlan::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }
}
