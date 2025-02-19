<?php

namespace App\Models\SupportTickets;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskComment extends Model
{
    use HasFactory;

    protected $fillable = ['task_id', 'user_id', 'content'];

    /**
     * Relación: un comentario pertenece a una tarea.
     */
    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    /**
     * Relación: un comentario pertenece a un usuario.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
