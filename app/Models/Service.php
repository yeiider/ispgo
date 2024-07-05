<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Invoice;

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

    public function internetPlan()
    {
        return $this->belongsTo(InternetPlan::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function generateInvoice($user_id, $notes = null): \App\Models\Invoice
    {
        $price = $this->monthly_fee ?? $this->internetPlan->monthly_price;
        $tax = $price * 0.19;
        $total = $price + $tax;

        $invoice = new Invoice();
        $invoice->service_id = $this->id;
        $invoice->customer_id = $this->customer_id;
        $invoice->user_id = $user_id; // Asumiendo que el usuario autenticado está generando la factura
        $invoice->subtotal = $price;
        $invoice->tax = $tax;
        $invoice->total = $total;
        $invoice->amount = 0;
        $invoice->outstanding_balance = $total;
        $invoice->issue_date = now();
        $invoice->due_date = now()->addDays(5);
        $invoice->status = 'unpaid';
        $invoice->payment_method = null;
        $invoice->notes = $notes;
        $invoice->save();

        return $invoice;
    }
}
