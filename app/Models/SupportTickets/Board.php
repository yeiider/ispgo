<?php

namespace App\Models\SupportTickets;

use App\Models\User; // Asegúrate de usar el modelo correcto.
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Board extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'created_by', 'updated_by'];

    protected $table = 'boards';

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

    /**
     * Relación: un tablero tiene muchas columnas.
     */
    public function columns()
    {
        return $this->hasMany(Column::class, 'board_id', 'id');
    }

    /**
     * Relación: pertenece a un creador (usuario).
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by', 'id')->withDefault();
    }

    /**
     * Relación: pertenece a un actualizador (usuario).
     */
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by', 'id')->withDefault();
    }
}
