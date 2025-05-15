<?php

namespace App\Models;

use App\Models\Inventory\Product;
use App\Models\Invoice\Invoice;
use App\Models\Services\Service;
use Illuminate\Database\Eloquent\Model;

class InvoiceItem extends Model
{
    protected $fillable = [
        'invoice_id', 'service_id', 'description',
        'quantity', 'unit_price', 'subtotal', 'metadata'
    ];

    protected $casts = [
        "metadata" => "array"
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}
