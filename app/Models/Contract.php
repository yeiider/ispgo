<?php

namespace App\Models;

use App\Models\Customers\Customer;
use App\Models\Services\Service;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Contract extends Model
{
    use HasFactory, \App\Traits\HasSignedUrls;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'contracts';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * The "type" of the primary key ID.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'customer_id',
        'service_id',
        'start_date',
        'end_date',
        'is_signed',
        'signed_at',
        'status',
        'contract_pdf_path',
        'cedula_path',
        'utility_bill_path'
    ];

    protected $casts = [
        'is_signed' => 'boolean',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'signed_at' => 'datetime',
    ];

    /**
     * Accessors for S3 Signed URLs
     */
    public function getContractPdfUrlAttribute()
    {
        return $this->generateSignedUrl($this->contract_pdf_path);
    }

    public function getCedulaUrlAttribute()
    {
        return $this->generateSignedUrl($this->cedula_path);
    }

    public function getUtilityBillUrlAttribute()
    {
        return $this->generateSignedUrl($this->utility_bill_path);
    }

    /**
     * Boot model events.
     */
    protected static function boot()
    {
        parent::boot();

        // Generate a UUID when creating a new Contract
        static::creating(function ($contract) {
            if (empty($contract->id)) {
                $contract->id = (string) Str::uuid();
            }
        });
    }

    /**
     * Get the customer associated with the contract.
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get the service associated with the contract.
     */
    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }
}
