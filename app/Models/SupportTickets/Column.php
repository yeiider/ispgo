<?php

namespace App\Models\SupportTickets;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Column extends Model
{
    use HasFactory;

    protected $fillable = ['board_id', 'title', 'position'];
    protected $table = 'columns';

    /**
     * Relación: una columna pertenece a un tablero.
     */
    public function board()
    {
        return $this->belongsTo(Board::class);
    }

    /**
     * Relación: una columna tiene muchas tareas.
     */
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
}
