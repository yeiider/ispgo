<?php

namespace App\Models\SupportTickets;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LabelTask extends Model
{
    use HasFactory;

    // Si tu tabla pivote no sigue el nombre por defecto, agrega el nombre explícito
    protected $table = 'label_task';

    // Si la tabla pivote no tiene timestamps, indícalo explícitamente
    public $timestamps = false;

    // Agrega si hay campos adicionales en la tabla pivote
    protected $fillable = ['label_id', 'task_id'];

    /**
     * Relación: pertenece a una etiqueta.
     */
    public function label()
    {
        return $this->belongsTo(Label::class);
    }

    /**
     * Relación: pertenece a una tarea.
     */
    public function task()
    {
        return $this->belongsTo(Task::class);
    }
}
