<?php

// src/Models/NapPort.php

namespace Ispgo\NapManager\Models;

use App\Models\Services\Service;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;
use Ispgo\NapManager\Models\NapBox;

class NapPort extends Model
{
    protected $fillable = [
        'nap_box_id',
        'port_number',
        'port_name',
        'status',
        'connection_type',
        'service_id',
        'code',
        'color',
        'last_signal_check',
        'signal_strength',
        'port_config',
        'notes',
        'technician_notes',
        'last_maintenance',
        'warranty_until'
    ];

    protected $casts = [
        'last_signal_check' => 'datetime',
        'last_maintenance' => 'date',
        'warranty_until' => 'date',
        'port_config' => 'array',
        'signal_strength' => 'decimal:2',
    ];

    // Constantes para estados
    const STATUS_AVAILABLE = 'available';
    const STATUS_OCCUPIED = 'occupied';
    const STATUS_DAMAGED = 'damaged';
    const STATUS_MAINTENANCE = 'maintenance';
    const STATUS_RESERVED = 'reserved';
    const STATUS_TESTING = 'testing';

    // Constantes para tipos de conexión
    const CONNECTION_FIBER = 'fiber';
    const CONNECTION_COAXIAL = 'coaxial';
    const CONNECTION_ETHERNET = 'ethernet';
    const CONNECTION_MIXED = 'mixed';

    // Relaciones
    public function napBox(): BelongsTo
    {
        return $this->belongsTo(NapBox::class, 'nap_box_id');
    }


    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class, 'service_id');
    }

    // Scopes
    public function scopeAvailable($query)
    {
        return $query->where('status', self::STATUS_AVAILABLE);
    }

    public function scopeOccupied($query)
    {
        return $query->where('status', self::STATUS_OCCUPIED);
    }

    public function scopeDamaged($query)
    {
        return $query->where('status', self::STATUS_DAMAGED);
    }

    public function scopeByNapBox($query, $napBoxId)
    {
        return $query->where('nap_box_id', $napBoxId);
    }

    public function scopeByConnectionType($query, $type)
    {
        return $query->where('connection_type', $type);
    }

    public function scopeNeedsMaintenance($query)
    {
        return $query->where(function ($q) {
            $q->where('status', self::STATUS_DAMAGED)
                ->orWhere('last_maintenance', '<', now()->subMonths(6))
                ->orWhere('signal_strength', '<', 70);
        });
    }

    public function scopeWithLowSignal($query, $threshold = 70)
    {
        return $query->where('signal_strength', '<', $threshold);
    }

    // Métodos de estado
    public function isAvailable(): bool
    {
        return $this->status === self::STATUS_AVAILABLE;
    }

    public function isOccupied(): bool
    {
        return $this->status === self::STATUS_OCCUPIED;
    }

    public function isDamaged(): bool
    {
        return $this->status === self::STATUS_DAMAGED;
    }

    public function isInMaintenance(): bool
    {
        return $this->status === self::STATUS_MAINTENANCE;
    }

    public function isReserved(): bool
    {
        return $this->status === self::STATUS_RESERVED;
    }

    // Métodos de gestión
    public function assignService($clientId, $serviceId = null, $speed = null)
    {
        if (!$this->isAvailable()) {
            throw new \Exception("El puerto {$this->port_number} no está disponible");
        }

        $this->update([
            'service_id' => $serviceId,
            'status' => self::STATUS_OCCUPIED,
        ]);

        return $this;
    }

    public function releaseFromClient()
    {
        if (!$this->isOccupied()) {
            throw new \Exception("El puerto {$this->port_number} no está ocupado");
        }

        // Desactivar servicios activos
        $this->services()->where('status', 'active')->update([
            'status' => 'inactive',
        ]);

        $this->update([
            'service_id' => null,
            'status' => self::STATUS_AVAILABLE,
        ]);

        return $this;
    }

    public function markAsDamaged($notes = null)
    {
        $this->update([
            'status' => self::STATUS_DAMAGED,
            'technician_notes' => $notes,
            'last_signal_check' => now()
        ]);

        // Si tenía un cliente, crear incidencia
        if ($this->client_id) {
            // Aquí podrías crear un ticket de soporte automático
            $this->createSupportTicket('Puerto dañado: ' . ($notes ?? 'Sin detalles'));
        }

        return $this;
    }

    public function markAsRepaired($technicianNotes = null)
    {
        if (!$this->isDamaged()) {
            throw new \Exception("El puerto {$this->port_number} no está marcado como dañado");
        }

        $this->update([
            'status' => $this->client_id ? self::STATUS_OCCUPIED : self::STATUS_AVAILABLE,
            'last_maintenance' => now(),
            'technician_notes' => $technicianNotes,
            'signal_strength' => null // Se actualizará en la próxima verificación
        ]);

        return $this;
    }

    public function updateSignalStrength($strength)
    {
        $this->update([
            'signal_strength' => $strength,
            'last_signal_check' => now()
        ]);

        // Alertar si la señal es muy baja
        if ($strength < 60 && $this->isOccupied()) {
            $this->createSignalAlert($strength);
        }

        return $this;
    }

    // Métodos de información
    public function getFullPortName(): string
    {
        return "{$this->napBox->code}-P{$this->port_number}";
    }

    public function getStatusLabel(): string
    {
        $labels = [
            self::STATUS_AVAILABLE => 'Disponible',
            self::STATUS_OCCUPIED => 'Ocupado',
            self::STATUS_DAMAGED => 'Dañado',
            self::STATUS_MAINTENANCE => 'Mantenimiento',
            self::STATUS_RESERVED => 'Reservado',
            self::STATUS_TESTING => 'Pruebas'
        ];

        return $labels[$this->status] ?? $this->status;
    }

    public function getConnectionTypeLabel(): string
    {
        $labels = [
            self::CONNECTION_FIBER => 'Fibra Óptica',
            self::CONNECTION_COAXIAL => 'Cable Coaxial',
            self::CONNECTION_ETHERNET => 'Ethernet',
            self::CONNECTION_MIXED => 'Mixto'
        ];

        return $labels[$this->connection_type] ?? $this->connection_type;
    }

    public function getSignalStatus(): string
    {
        if (!$this->signal_strength) {
            return 'Sin datos';
        }

        if ($this->signal_strength >= 80) {
            return 'Excelente';
        } elseif ($this->signal_strength >= 70) {
            return 'Buena';
        } elseif ($this->signal_strength >= 60) {
            return 'Regular';
        } else {
            return 'Baja';
        }
    }

    public function getSignalColor(): string
    {
        if (!$this->signal_strength) {
            return 'gray';
        }

        if ($this->signal_strength >= 80) {
            return 'green';
        } elseif ($this->signal_strength >= 70) {
            return 'blue';
        } elseif ($this->signal_strength >= 60) {
            return 'yellow';
        } else {
            return 'red';
        }
    }

    public function getDaysWithoutMaintenance(): int
    {
        if (!$this->last_maintenance) {
            return $this->created_at->diffInDays(now());
        }

        return $this->last_maintenance->diffInDays(now());
    }

    public function needsMaintenance(): bool
    {
        return $this->getDaysWithoutMaintenance() > 180 || // 6 meses sin mantenimiento
            $this->signal_strength < 70 ||
            $this->isDamaged();
    }

    // Métodos para reportes
    public function getPortSummary(): array
    {
        return [
            'id' => $this->id,
            'full_name' => $this->getFullPortName(),
            'port_number' => $this->port_number,
            'status' => $this->status,
            'status_label' => $this->getStatusLabel(),
            'connection_type' => $this->connection_type,
            'connection_type_label' => $this->getConnectionTypeLabel(),
            'signal_strength' => $this->signal_strength,
            'signal_status' => $this->getSignalStatus(),
            'signal_color' => $this->getSignalColor(),
            'client_name' => $this->client?->name,
            'days_active' => $this->getUptime(),
            'needs_maintenance' => $this->needsMaintenance(),
            'days_without_maintenance' => $this->getDaysWithoutMaintenance()
        ];
    }

    // Métodos auxiliares privados
    private function createSupportTicket($description)
    {
        // Implementar lógica para crear ticket de soporte
        // Esto dependería de tu sistema de tickets
    }

    private function createSignalAlert($strength)
    {
        // Implementar lógica para crear alerta de señal baja
        // Podría ser email, notificación push, etc.
    }

    // Validaciones personalizadas
    public static function validatePortNumber($napBoxId, $portNumber, $excludeId = null)
    {
        $query = self::where('nap_box_id', $napBoxId)
            ->where('port_number', $portNumber);

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return !$query->exists();
    }

    public static function getAvailablePortNumber($napBoxId)
    {
        $napBox = NapBox::find($napBoxId);
        if (!$napBox) {
            throw new \Exception('Caja NAP no encontrada');
        }

        $occupiedPorts = self::where('nap_box_id', $napBoxId)
            ->pluck('port_number')
            ->toArray();

        for ($i = 1; $i <= $napBox->capacity; $i++) {
            if (!in_array($i, $occupiedPorts)) {
                return $i;
            }
        }

        throw new \Exception('No hay puertos disponibles en esta caja NAP');
    }

    // Boot method para eventos del modelo
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($port) {
            // Auto-asignar número de puerto si no se especifica
            if (!$port->port_number) {
                $port->port_number = self::getAvailablePortNumber($port->nap_box_id);
            }

            // Generar nombre del puerto si no se especifica
            if (!$port->port_name) {
                $port->port_name = "Puerto {$port->port_number}";
            }
        });

        static::updating(function ($port) {
            // Log de cambios de estado
            if ($port->isDirty('status')) {
                $port->logStatusChange($port->getOriginal('status'), $port->status);
            }
        });
    }

    private function logStatusChange($oldStatus, $newStatus)
    {
        // Implementar logging de cambios de estado
        \Log::info("Puerto {$this->getFullPortName()} cambió de estado", [
            'port_id' => $this->id,
            'old_status' => $oldStatus,
            'new_status' => $newStatus,
            'client_id' => $this->client_id,
            'timestamp' => now()
        ]);
    }
}
