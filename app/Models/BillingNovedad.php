<?php

namespace App\Models;

use App\Models\Customers\Customer;
use App\Models\Inventory\Product;
use App\Models\Invoice\Invoice;
use App\Models\Services\Service;
use Carbon\Carbon;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

/**
 * @property int $id
 * @property int $service_id
 * @property int $customer_id
 * @property string $type
 * @property float $amount
 * @property string|null $description
 * @property array|null $rule
 * @property string|null $effective_period
 * @property bool $applied
 * @property int|null $invoice_id
 * @property int $created_by
 * @property int|null $product_id
 * @property int|null $quantity
 * @property float|null $unit_price
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @mixin Builder
 */
class BillingNovedad extends Model
{
    use HasFactory;

    protected $table = 'billing_novedades';

    /** ----------------- TIPOS PERMITIDOS ----------------- */
    public const T_SALDO_FAVOR = 'saldo_favor';
    public const T_CARGO_ADICIONAL = 'cargo_adicional';
    public const T_PRORRATEO_INI = 'prorrateo_inicial';
    public const T_PRORRATEO_FIN = 'prorrateo_cancelacion';
    public const T_CAMBIO_PLAN = 'cambio_plan';
    public const T_DESCUENTO_PROMO = 'descuento_promocional';
    public const T_CARGO_RECONEXION = 'cargo_reconexion';
    public const T_MORA = 'mora';
    public const T_NOTA_CREDITO = 'nota_credito';
    public const T_COMPENSACION = 'compensacion';
    public const T_EXCESO_CONSUMO = 'exceso_consumo';
    public const T_IMPUESTO = 'impuesto';
    public const T_ENTREGA_PRODUCTO = 'product_delivery';

    public const TYPES = [
        self::T_SALDO_FAVOR,
        self::T_CARGO_ADICIONAL,
        self::T_PRORRATEO_INI,
        self::T_PRORRATEO_FIN,
        self::T_CAMBIO_PLAN,
        self::T_DESCUENTO_PROMO,
        self::T_CARGO_RECONEXION,
        self::T_MORA,
        self::T_NOTA_CREDITO,
        self::T_COMPENSACION,
        self::T_EXCESO_CONSUMO,
        self::T_IMPUESTO,
        self::T_ENTREGA_PRODUCTO,
    ];

    /** Campos asignables en masa */
    protected $fillable = [
        'service_id',
        'customer_id',
        'type',
        'amount',
        'description',
        'rule',
        'effective_period',
        'applied',
        'invoice_id',
        'created_by',
        'product_lines',
        'quantity',
        'unit_price',
    ];

    /** Casts */
    protected $casts = [
        'amount' => 'decimal:2',
        'rule' => 'array',
        'applied' => 'boolean',
        'effective_period' => 'date',
        'quantity' => 'integer',
        'unit_price' => 'decimal:2',
        'product_lines' => 'array',
    ];

    /* ======================================================
     *  Relationships
     * ====================================================*/

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /* ======================================================
     *  Accessors & helpers
     * ====================================================*/

    /** true si la novedad descuenta (monto < 0) */
    public function getIsDiscountAttribute(): bool
    {
        return $this->amount < 0;
    }

    /** Marca la novedad como aplicada y enlaza la factura */
    public function markAsApplied(Invoice $invoice): self
    {
        $this->update([
            'applied' => true,
            'invoice_id' => $invoice->id,
        ]);

        return $this;
    }

    /* ======================================================
     *  Scopes
     * ====================================================*/

    /** Novedades pendientes (no aplicadas) */
    public function scopePending(Builder $query): Builder
    {
        return $query->where('applied', false);
    }

    /** Novedades para un periodo (YYYY-MM-01) */
    public function scopeForPeriod(Builder $query, DateTimeInterface $period = null)   // <- aquí
    {
        return $query->whereDate('effective_period', $period->format('Y-m-d'));
    }
    /** Novedades asociadas a un servicio específico */
    public function scopeForService(Builder $q, int $serviceId): Builder
    {
        return $q->where('service_id', $serviceId);
    }

    /* ======================================================
     *  Model events
     * ====================================================*/

    protected static function booted(): void
    {
        // Para entregas de producto: calcular automáticamente el monto
        static::creating(function (self $nov) {
            $nov->customer_id = $nov->service->customer->id;
            $nov->created_by = Auth::id();
            $registry = app(\App\Services\Billing\Calculators\NovedadCalculatorRegistry::class);
            $calculator = $registry->for($nov->type);
            $nov->amount = $calculator->calculate($nov ?? [], $nov->service);

        });
        static::created(function (self $nov) {
            if ($nov->type === self::T_ENTREGA_PRODUCTO) {
                /*InventoryService::deduct($nov->product_id, $nov->quantity, [
                    'novedad_id' => $nov->id,
                    'reason'     => 'Entrega al cliente mes vencido',
                ]);*/
            }
        });
    }
}
