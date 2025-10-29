<?php

namespace App\Mail;

use App\Models\Cotizacion;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class CotizacionCreadaMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public Cotizacion $cotizacion;

    /**
     * Datos adicionales enviados en la solicitud (por ejemplo, detalles del plan)
     * @var array
     */
    public array $payload;

    /**
     * Número máximo de intentos
     * @var int
     */
    public $tries = 3;

    /**
     * Tiempo de espera antes de que el trabajo falle (en segundos)
     * @var int
     */
    public $timeout = 120;

    /**
     * Create a new message instance.
     */
    public function __construct(Cotizacion $cotizacion, array $payload = [])
    {
        $this->cotizacion = $cotizacion;
        $this->payload = $payload;

        // Especificar la conexión de cola correctamente
        // Si quieres usar una cola específica dentro de redis:
        $this->onConnection('redis')->onQueue('redis');
    }

    /**
     * Build the message.
     */
    public function build(): self
    {
        // Usar valores seguros con fallback para evitar errores
        $planNombre = $this->payload['plan_nombre']
            ?? $this->payload['plan']['nombre'] ?? null
            ?? $this->cotizacion->plan
            ?? '-';

        $planVelocidad = $this->payload['plan_velocidad']
            ?? $this->payload['plan']['velocidad'] ?? null
            ?? '-';

        $planPrecio = $this->payload['plan_precio']
            ?? $this->payload['plan']['precio'] ?? null
            ?? '-';

        return $this
            ->subject('¡Solicitud recibida - Raíces!')
            ->view('emails.cotizacion_creada', [
                'nombre' => $this->cotizacion->nombre ?? '',
                'apellido' => $this->cotizacion->apellido ?? '',
                'email' => $this->cotizacion->email ?? '',
                'telefono' => $this->cotizacion->telefono ?? '',
                'direccion' => $this->cotizacion->direccion ?? '',
                'ciudad' => $this->cotizacion->ciudad ?? '',
                'plan_nombre' => $planNombre,
                'plan_velocidad' => $planVelocidad,
                'plan_precio' => $planPrecio,
            ]);
    }

    /**
     * Manejar el fallo del trabajo
     */
    public function failed(\Throwable $exception): void
    {
        // Registrar el error detalladamente
        \Log::error('Error al enviar email de cotización creada', [
            'cotizacion_id' => $this->cotizacion->id ?? null,
            'email' => $this->cotizacion->email ?? null,
            'error' => $exception->getMessage(),
            'trace' => $exception->getTraceAsString()
        ]);
    }
}
