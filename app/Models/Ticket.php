<?php

namespace App\Models;

use App\Events\UserAssignedToTicket;
use App\Models\Customers\Customer;
use App\Models\Services\Service;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    /**
     * Assign a user to the ticket.
     *
     * @param int|User $user
     * @return void
     */
    public function assignUser(User|int $user): void
    {
        if ($user instanceof User) {
            $this->user_id = $user->id;
        } else {
            $this->user_id = $user;
        }

        $this->save();

        event(new UserAssignedToTicket($this));
    }

    /**
     * Get the comments for the ticket.
     */
    public function comments(): HasMany
    {
        return $this->hasMany(TicketComment::class);
    }

    /**
     * Get the attachments for the ticket.
     */
    public function attachments(): HasMany
    {
        return $this->hasMany(TicketAttachment::class);
    }

    /**
     * Get the labels for the ticket.
     */
    public function labels(): BelongsToMany
    {
        return $this->belongsToMany(TicketLabel::class, 'ticket_label', 'ticket_id', 'ticket_label_id')
            ->withTimestamps();
    }
}
