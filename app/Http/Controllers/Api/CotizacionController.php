<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cotizacion;
use App\Mail\CotizacionCreadaMail;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class CotizacionController extends Controller
{
    /**
     * Store a newly created cotizacion in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'nombre' => ['required', 'string', 'max:100'],
            'apellido' => ['required', 'string', 'max:100'],
            'email' => ['required', 'string', 'email', 'max:150'],
            'telefono' => ['required', 'string', 'max:50'],
            'direccion' => ['required', 'string', 'max:255'],
            'ciudad' => ['required', 'string', 'max:120'],
            'plan' => ['required', 'string', 'max:150'],
            'canal' => ['required', Rule::in(['web', 'whatsapp'])],
            'estado' => ['sometimes', Rule::in(['pendiente', 'atendida'])],
            'notas' => ['nullable', 'string'],
            'metadata' => ['nullable', 'array'],
        ]);

        // Default estado to pendiente if not provided
        if (!isset($validated['estado'])) {
            $validated['estado'] = 'pendiente';
        }

        // Business rule: if a quotation exists with the same phone and same status, do not create
        $exists = Cotizacion::query()
            ->where('telefono', $validated['telefono'])
            ->where('estado', $validated['estado'])
            ->exists();

        if ($exists) {
            return response()->json([
                'message' => 'Ya existe una cotización con el mismo teléfono y estado: '.$validated['estado'],
                'status' => 'duplicate',
            ], 422);
        }

        $cotizacion = Cotizacion::create($validated);

        // Enviar correo en cola (Redis) sin bloquear la respuesta
        try {
            $payload = array_filter([
                'plan_nombre'   => $request->input('plan_nombre', $validated['plan'] ?? null),
                'plan_velocidad'=> $request->input('plan_velocidad'),
                'plan_precio'   => $request->input('plan_precio'),
                'plan'          => $request->input('metadata.plan'),
            ], fn($v) => !is_null($v) && $v !== '');

            // Si viene un arreglo de metadata, lo pasamos completo para soportar estructuras personalizadas
            $metadata = $request->input('metadata', []);
            if (is_array($metadata)) {
                $payload = array_merge($metadata, $payload);
            }

            Mail::to($cotizacion->email)->queue(new CotizacionCreadaMail($cotizacion, $payload));
        } catch (\Throwable $e) {
            Log::warning('Error al encolar correo de cotización: '.$e->getMessage(), [
                'cotizacion_id' => $cotizacion->id,
            ]);
        }

        return response()->json([
            'message' => 'Cotización creada correctamente',
            'data' => $cotizacion,
        ], 201);
    }
}
