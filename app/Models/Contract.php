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
    use HasFactory;

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
        'signed_at'
    ];

    protected $casts = [
        'is_signed' => 'boolean',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'signed_at' => 'datetime',

    ];

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
