<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SupportTickets\Task;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TaskControllerApi extends Controller
{
    /**
     * Muestra un listado de todas las tareas.
     */
    public function index()
    {
        // Ejemplo: Devolver todas las tareas con sus relaciones asociadas
        // Para filtrar por columna, cliente, etiqueta, etc., podrías implementar parámetros en la query.
        $tasks = Task::with(['column.board', 'creator', 'customer', 'service', 'labels', 'comments'])->get();
        return response()->json($tasks);
    }

    /**
     * Crea una nueva tarea.
     */
    public function store(Request $request)
    {
        // Ejemplo de validación
        $validatedData = $request->validate([
            'column_id'   => 'required|exists:columns,id',
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'created_by'  => 'required|exists:users,id',
            'updated_by'  => 'nullable|exists:users,id',
            'customer_id' => 'nullable|exists:customers,id',
            'service_id'  => 'nullable|exists:services,id',
            'due_date'    => 'nullable|date',
            'priority'    => [
                'nullable',
                Rule::in(['low', 'normal', 'high']),
            ],
            // Puedes añadir más validaciones...
        ]);

        // Crear la tarea
        $task = Task::create($validatedData);

        // Si quieres relacionar etiquetas justo al crear la tarea,
        // podrías recibir label_ids en la solicitud y relacionarlas aquí:
        if ($request->has('label_ids') && is_array($request->label_ids)) {
            $task->labels()->sync($request->label_ids);
        }

        return response()->json($task, 201);
    }

    /**
     * Muestra una tarea en específico.
     */
    public function show($id)
    {
        // Cargar la tarea con sus relaciones
        $task = Task::with(['column.board', 'creator', 'customer', 'service', 'labels', 'comments'])->findOrFail($id);
        return response()->json($task);
    }

    /**
     * Actualiza los datos de una tarea existente.
     */
    public function update(Request $request, $id)
    {
        $task = Task::findOrFail($id);

        // Ejemplo de validación
        $validatedData = $request->validate([
            'column_id'   => 'sometimes|exists:columns,id',
            'title'       => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'updated_by'  => 'nullable|exists:users,id',
            'customer_id' => 'nullable|exists:customers,id',
            'service_id'  => 'nullable|exists:services,id',
            'due_date'    => 'nullable|date',
            'priority'    => [
                'nullable',
                Rule::in(['low', 'normal', 'high']),
            ],
            // Agrega más validaciones si fuera necesario...
        ]);

        // Actualizar la tarea
        $task->update($validatedData);

        // Manejo de etiquetas si se incluyen en la solicitud
        if ($request->has('label_ids') && is_array($request->label_ids)) {
            $task->labels()->sync($request->label_ids);
        }

        return response()->json($task);
    }

    /**
     * Elimina una tarea.
     */
    public function destroy($id)
    {
        $task = Task::findOrFail($id);
        $task->delete();

        return response()->json([
            'message' => 'Tarea eliminada correctamente.'
        ], 200);
    }
}
