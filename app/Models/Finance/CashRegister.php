<?php

namespace App\Models\Finance;

use App\Models\Router;
use App\Models\User;
use App\Models\Invoice\Invoice;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

/**
 * CashRegister Model
 *
 * Representa una caja registradora asignada a un router (zona) específico.
 * Permite gestionar los pagos diarios de facturas por zona.
 */
class CashRegister extends Model
{
    use HasFactory;

    const STATUS_OPEN = 'open';
    const STATUS_CLOSED = 'closed';

    protected $fillable = [
        'name',
        'router_id',
        'user_id',
        'initial_balance',
        'current_balance',
        'status',
        'opened_at',
        'closed_at',
        'notes',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'initial_balance' => 'decimal:2',
        'current_balance' => 'decimal:2',
        'opened_at' => 'datetime',
        'closed_at' => 'datetime',
    ];

    /**
     * Relación con el router (zona)
     */
    public function router()
    {
        return $this->belongsTo(Router::class);
    }

    /**
     * Usuario asignado a esta caja
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Usuario que creó el registro
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Usuario que actualizó el registro
     */
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Cierres de caja
     */
    public function closures()
    {
        return $this->hasMany(CashRegisterClosure::class)->orderBy('closure_date', 'desc');
    }

    /**
     * Facturas asociadas a esta caja
     */
    public function invoices()
    {
        return $this->hasMany(Invoice::class, 'daily_box_id');
    }

    /**
     * Transacciones (si existe el modelo)
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * Scope: Solo cajas abiertas
     */
    public function scopeOpen(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_OPEN);
    }

    /**
     * Scope: Solo cajas cerradas
     */
    public function scopeClosed(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_CLOSED);
    }

    /**
     * Scope: Filtrar por router
     */
    public function scopeByRouter(Builder $query, int $routerId): Builder
    {
        return $query->where('router_id', $routerId);
    }

    /**
     * Scope: Filtrar por usuario
     */
    public function scopeByUser(Builder $query, int $userId): Builder
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope: Filtrar por routers del usuario autenticado
     */
    public function scopeByUserRouters(Builder $query): Builder
    {
        $user = Auth::user();

        if (!$user) {
            return $query;
        }

        $routerIds = $user->getRouterIds();

        if (empty($routerIds)) {
            return $query;
        }

        return $query->whereIn('router_id', $routerIds);
    }

    /**
     * Abrir la caja
     */
    public function open(): void
    {
        $this->status = self::STATUS_OPEN;
        $this->opened_at = now();
        $this->closed_at = null;
        $this->save();
    }

    /**
     * Cerrar la caja
     */
    public function close(): void
    {
        $this->status = self::STATUS_CLOSED;
        $this->closed_at = now();
        $this->save();
    }

    /**
     * Verificar si la caja está abierta
     */
    public function isOpen(): bool
    {
        return $this->status === self::STATUS_OPEN;
    }

    /**
     * Verificar si la caja está cerrada
     */
    public function isClosed(): bool
    {
        return $this->status === self::STATUS_CLOSED;
    }

    /**
     * Boot del modelo
     */
    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($model) {
            $model->created_by = Auth::id();
            $model->updated_by = Auth::id();

            if (!$model->opened_at) {
                $model->opened_at = now();
            }
        });

        static::updating(function ($model) {
            $model->updated_by = Auth::id();
        });
    }
}
