<?php

namespace App\Models\Finance;

use App\Models\User;
use App\Models\Invoice\Invoice;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

/**
 * CashRegisterClosure Model
 *
 * Representa un cierre de caja diario con toda la información de pagos,
 * métodos de pago, descuentos y ajustes del período.
 */
class CashRegisterClosure extends Model
{
    use HasFactory;

    const STATUS_PROCESSING = 'processing';
    const STATUS_COMPLETED = 'completed';
    const STATUS_FAILED = 'failed';

    protected $fillable = [
        'cash_register_id',
        'user_id',
        'closure_date',
        'opening_balance',
        'closing_balance',
        'expected_balance',
        'difference',
        'total_cash',
        'total_transfer',
        'total_card',
        'total_online',
        'total_other',
        'total_invoices',
        'paid_invoices',
        'total_collected',
        'total_discounts',
        'total_adjustments',
        'payment_details',
        'invoice_summary',
        'metadata',
        'status',
        'notes',
        'processed_at'
    ];

    protected $casts = [
        'closure_date' => 'date',
        'opening_balance' => 'decimal:2',
        'closing_balance' => 'decimal:2',
        'expected_balance' => 'decimal:2',
        'difference' => 'decimal:2',
        'total_cash' => 'decimal:2',
        'total_transfer' => 'decimal:2',
        'total_card' => 'decimal:2',
        'total_online' => 'decimal:2',
        'total_other' => 'decimal:2',
        'total_collected' => 'decimal:2',
        'total_discounts' => 'decimal:2',
        'total_adjustments' => 'decimal:2',
        'payment_details' => 'array',
        'invoice_summary' => 'array',
        'metadata' => 'array',
        'processed_at' => 'datetime',
    ];

    /**
     * Caja registradora asociada
     */
    public function cashRegister()
    {
        return $this->belongsTo(CashRegister::class);
    }

    /**
     * Usuario que realizó el cierre
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Facturas incluidas en este cierre
     * Basado en la fecha de pago y la caja
     */
    public function invoices()
    {
        return Invoice::where('daily_box_id', $this->cash_register_id)
            ->whereDate('updated_at', $this->closure_date)
            ->where('status', 'paid');
    }

    /**
     * Scope: Solo cierres completados
     */
    public function scopeCompleted(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_COMPLETED);
    }

    /**
     * Scope: Solo cierres en procesamiento
     */
    public function scopeProcessing(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_PROCESSING);
    }

    /**
     * Scope: Solo cierres fallidos
     */
    public function scopeFailed(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_FAILED);
    }

    /**
     * Scope: Por rango de fechas
     */
    public function scopeDateRange(Builder $query, Carbon $startDate, Carbon $endDate): Builder
    {
        return $query->whereBetween('closure_date', [$startDate, $endDate]);
    }

    /**
     * Scope: Por fecha específica
     */
    public function scopeByDate(Builder $query, Carbon $date): Builder
    {
        return $query->whereDate('closure_date', $date);
    }

    /**
     * Marcar como completado
     */
    public function markAsCompleted(): void
    {
        $this->status = self::STATUS_COMPLETED;
        $this->processed_at = now();
        $this->save();
    }

    /**
     * Marcar como fallido
     */
    public function markAsFailed(string $reason = null): void
    {
        $this->status = self::STATUS_FAILED;
        $this->processed_at = now();

        if ($reason) {
            $metadata = $this->metadata ?? [];
            $metadata['failure_reason'] = $reason;
            $this->metadata = $metadata;
        }

        $this->save();
    }

    /**
     * Verificar si hay diferencia en el cierre
     */
    public function hasDifference(): bool
    {
        return abs($this->difference) > 0.01;
    }

    /**
     * Obtener el total esperado
     */
    public function getTotalExpectedAttribute(): float
    {
        return $this->opening_balance + $this->total_collected;
    }

    /**
     * Calcular totales por método de pago
     */
    public function calculatePaymentTotals(): void
    {
        $invoices = $this->invoices()->get();

        $this->total_cash = $invoices->where('payment_method', 'cash')->sum('amount');
        $this->total_transfer = $invoices->where('payment_method', 'transfer')->sum('amount');
        $this->total_card = $invoices->where('payment_method', 'card')->sum('amount');
        $this->total_online = $invoices->where('payment_method', 'online')->sum('amount');

        // Otros métodos
        $otherMethods = ['other', 'check', 'cryptocurrency'];
        $this->total_other = $invoices->whereIn('payment_method', $otherMethods)->sum('amount');
    }

    /**
     * Generar resumen detallado del cierre
     */
    public function generateSummary(): array
    {
        $invoices = $this->invoices()->with(['customer', 'adjustments'])->get();

        return [
            'total_invoices' => $invoices->count(),
            'paid_invoices' => $invoices->where('status', 'paid')->count(),
            'total_collected' => $invoices->sum('amount'),
            'total_discounts' => $invoices->sum('discount'),
            'payment_methods' => [
                'cash' => $this->total_cash,
                'transfer' => $this->total_transfer,
                'card' => $this->total_card,
                'online' => $this->total_online,
                'other' => $this->total_other,
            ],
            'adjustments' => $invoices->sum(function ($invoice) {
                return $invoice->adjustments->sum('amount');
            }),
        ];
    }
}
