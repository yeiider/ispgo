<?php

namespace App\Mail;

use App\Models\Cotizacion;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CotizacionCreadaMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * Nombre de la cola donde se encolará este mailable.
     *
     * @var string
     */
    public $queue = 'redis';

    public Cotizacion $cotizacion;

    /**
     * Datos adicionales enviados en la solicitud (por ejemplo, detalles del plan)
     * @var array
     */
    public array $payload;

    /**
     * Create a new message instance.
     */
    public function __construct(Cotizacion $cotizacion, array $payload = [])
    {
        $this->cotizacion = $cotizacion;
        $this->payload = $payload;
    }

    /**
     * Build the message.
     */
    public function build(): self
    {
        $planNombre = $this->payload['plan_nombre'] ?? $this->cotizacion->plan ?? '-';
        $planVelocidad = $this->payload['plan_velocidad'] ?? ($this->payload['plan']['velocidad'] ?? '-') ;
        $planPrecio = $this->payload['plan_precio'] ?? ($this->payload['plan']['precio'] ?? '-') ;

        return $this
            ->subject('¡Solicitud recibida - Raíces!')
            ->view('emails.cotizacion_creada', [
                'nombre' => $this->cotizacion->nombre,
                'apellido' => $this->cotizacion->apellido,
                'email' => $this->cotizacion->email,
                'telefono' => $this->cotizacion->telefono,
                'direccion' => $this->cotizacion->direccion,
                'ciudad' => $this->cotizacion->ciudad,
                'plan_nombre' => $planNombre,
                'plan_velocidad' => $planVelocidad,
                'plan_precio' => $planPrecio,
            ]);
    }
}
