<?php

namespace App\Models;

use App\Models\Customers\Customer;
use App\Models\Services\Service;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'service_id',
        'issue_type',
        'priority',
        'status',
        'title',
        'description',
        'user_id',
        'resolution_notes',
        'attachments',
        'contact_method',
        'closed_at'
    ];

    protected $casts = [
        'closed_at' => 'date'
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the customer that owns the ticket.
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get the service that is associated with the ticket.
     */
    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    /**
     * Scope a query to only include tickets of a given status.
     *
     * @param Builder $query
     * @param string $status
     * @return Builder
     */
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope a query to only include tickets of a given priority.
     *
     * @param Builder $query
     * @param string $priority
     * @return Builder
     */
    public function scopePriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    /**
     * Set the closed_at attribute when status is set to Closed.
     *
     * @param string $value
     * @return void
     */
    public function setStatusAttribute($value)
    {
        $this->attributes['status'] = $value;

        if ($value == 'closed') {
            $this->attributes['closed_at'] = now();
        } else {
            $this->attributes['closed_at'] = null;
        }
    }
}
