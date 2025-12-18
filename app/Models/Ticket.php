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
        'resolution_notes',
        'contact_method',
        'closed_at',
        'labels'
    ];

    protected $casts = [
        'closed_at' => 'date',
        'labels' => 'array'
    ];

    /**
     * Get the labels attribute, ensuring it's always an array.
     */
    public function getLabelsAttribute($value)
    {
        if (is_null($value) || $value === '') {
            return [];
        }

        // If it's already decoded as array (via cast)
        if (is_array($value)) {
            return $value;
        }

        // Try to decode if it's still a string
        $decoded = json_decode($value, true);
        return is_array($decoded) ? $decoded : [];
    }


    /**
     * Get the users assigned to the ticket.
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)->withTimestamps();
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
        $userId = $user instanceof User ? $user->id : $user;

        // Check if the user is already assigned to the ticket
        if (!$this->users()->where('user_id', $userId)->exists()) {
            $this->users()->attach($userId);
            event(new UserAssignedToTicket($this));
        }
    }

    /**
     * Assign multiple users to the ticket.
     *
     * @param array $userIds
     * @return void
     */
    public function assignUsers(array $userIds): void
    {
        $this->users()->syncWithoutDetaching($userIds);
        event(new UserAssignedToTicket($this));
    }

    /**
     * Remove a user from the ticket.
     *
     * @param int|User $user
     * @return void
     */
    public function removeUser(User|int $user): void
    {
        $userId = $user instanceof User ? $user->id : $user;
        $this->users()->detach($userId);
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
     * Add a label to the ticket.
     *
     * @param string $name
     * @param string $color
     * @return void
     */
    public function addLabel(string $name, string $color = '#3498db'): void
    {
        $labels = $this->labels ?? [];

        // Check if label already exists
        foreach ($labels as $label) {
            if ($label['name'] === $name) {
                return;
            }
        }

        $labels[] = [
            'name' => $name,
            'color' => $color
        ];

        $this->labels = $labels;
        $this->save();
    }

    /**
     * Remove a label from the ticket.
     *
     * @param string $name
     * @return void
     */
    public function removeLabel(string $name): void
    {
        if (!$this->labels) {
            return;
        }

        $labels = array_filter($this->labels, function ($label) use ($name) {
            return $label['name'] !== $name;
        });

        $this->labels = array_values($labels);
        $this->save();
    }

    /**
     * Check if the ticket has a specific label.
     *
     * @param string $name
     * @return bool
     */
    public function hasLabel(string $name): bool
    {
        if (!$this->labels) {
            return false;
        }

        foreach ($this->labels as $label) {
            if ($label['name'] === $name) {
                return true;
            }
        }

        return false;
    }

    /**
     * Scope: tickets asignados a un usuario concreto.
     *
     * Uso:
     *   Ticket::assignedTo($userId)->get();
     */
    public function scopeAssignedTo(Builder $query, int $userId): Builder
    {
        return $query->whereHas(
            'users',
            fn (Builder $q) => $q->where('users.id', $userId)
        );
    }

    /**
     * Devuelve los tickets asignados al usuario autenticado
     * (si no hay usuario autenticado, devuelve una colección vacía).
     *
     * Uso:
     *   $tickets = Ticket::forAuthenticatedUser();
     */
    public static function forAuthenticatedUser()
    {
        $userId = auth()->id();

        return $userId
            ? static::assignedTo($userId)->get()
            : collect();
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

            // If super admin always sees all, or if no router assigned, show all
            if ($user->isSuperAdmin() || !$user->router_id) {
                return;
            }

            // Filter by router_id through customer relationship (applies to admin with router_id and regular users with router_id)
            $builder->whereHas('customer', function ($query) use ($user) {
                $query->where('router_id', $user->router_id);
            });
        });
    }
}
