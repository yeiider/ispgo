<?php

namespace App\Models\SupportTickets;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskAttachment extends Model
{
    use HasFactory;

    protected $table = 'task_attachment';

    protected $fillable = ['task_id', 'file_path', 'file_name', 'uploaded_by'];

    /**
     * Relación: un adjunto pertenece a una tarea.
     */
    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    /**
     * Relación: un adjunto pertenece a un usuario.
     */
    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
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
            $board->uploaded_by = auth()->id();
        });

        // Antes de actualizar un registro (incluir el actualizador)
        static::updating(function ($board) {
            // Asignar el ID del usuario autenticado como 'updated_by'.
            $board->uploaded_by = auth()->id();
        });
    }
}
