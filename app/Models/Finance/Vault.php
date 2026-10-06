<?php

namespace App\Models\Finance;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Vault Model
 *
 * Representa una caja fuerte o cuenta general independiente de los cierres diarios.
 */
class Vault extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'initial_balance',
        'current_balance',
        'notes',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'initial_balance' => 'decimal:2',
        'current_balance' => 'decimal:2',
    ];

    /**
     * Usuarios asignados como administradores de esta caja fuerte
     */
    public function admins()
    {
        return $this->belongsToMany(User::class, 'vault_user');
    }

    /**
     * Creador
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Actualizador
     */
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Transferencias enviadas desde esta bóveda
     */
    public function transfersSent()
    {
        return $this->morphMany(CashTransfer::class, 'sender');
    }

    /**
     * Transferencias recibidas en esta bóveda
     */
    public function transfersReceived()
    {
        return $this->morphMany(CashTransfer::class, 'receiver');
    }
}
