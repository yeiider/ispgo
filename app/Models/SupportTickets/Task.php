<?php

namespace App\Models\SupportTickets;

use App\Models\Customers\Customer;
use App\Models\Services\Service;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'column_id',
        'title',
        'description',
        'created_by',
        'updated_by',
        'customer_id',
        'service_id',
        'due_date',
        'priority'
    ];

    protected $casts = [
        'due_date' => 'datetime'
    ];

    /**
     * Relación: pertenece a una columna.
     */
    public function column()
    {
        return $this->belongsTo(Column::class);
    }

    /**
     * Relación: pertenece a un creador (usuario).
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Relación: pertenece a un actualizador (usuario).
     */
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Relación: pertenece a un cliente.
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Relación: pertenece a un servicio.
     */
    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    /**
     * Relación: una tarea tiene muchos comentarios.
     */
    public function comments()
    {
        return $this->hasMany(TaskComment::class);
    }

    /**
     * Relación: una tarea tiene muchos adjuntos.
     */
    public function attachments()
    {
        return $this->hasMany(TaskAttachment::class);
    }

    /**
     * Relación: una tarea puede tener muchas etiquetas.
     */
    public function labels()
    {
        return $this->belongsToMany(Label::class, 'label_task');
    }

    /**
     * Boot method for hooking into model events.
     */
    protected static function boot()
    {
        parent::boot();

        // Antes de crear un registro (incluir el creador)
        static::creating(function ($board) {
            // Asignar el ID del usuario autenticado como 'created_by'.
            $board->created_by = auth()->id();
        });

        // Antes de actualizar un registro (incluir el actualizador)
        static::updating(function ($board) {
            // Asignar el ID del usuario autenticado como 'updated_by'.
            $board->updated_by = auth()->id();
        });
    }
}
