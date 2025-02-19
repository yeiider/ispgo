<?php

namespace App\Models\SupportTickets;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Label extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'color'];

    /**
     * Relación: una etiqueta puede ser usada en muchas tareas.
     */
    public function tasks()
    {
        return $this->belongsToMany(Task::class, 'label_task');
    }
}
