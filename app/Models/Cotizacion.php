<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cotizacion extends Model
{
    use HasFactory;

    protected $table = 'cotizaciones';

    protected $fillable = [
        'nombre',
        'apellido',
        'email',
        'telefono',
        'direccion',
        'ciudad',
        'plan',
        'canal',
        'estado',
        'notas',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];
}
