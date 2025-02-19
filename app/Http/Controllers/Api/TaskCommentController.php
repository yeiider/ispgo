<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SupportTickets\TaskComment;
use Illuminate\Http\Request;

class TaskCommentController extends Controller
{
    /**
     * Lista todos los comentarios, opcionalmente filtra por tarea.
     */
    public function index(Request $request)
    {
        // Si deseas filtrar por tarea, puedes usar algo como:
        // ?task_id=123 en la URL
        $query = TaskComment::query();

        if ($request->has('task_id')) {
            $query->where('task_id', $request->task_id);
        }

        // Ejemplo de eager loading de relaciones
        $comments = $query->with(['task', 'user'])->get();

        return response()->json($comments);
    }

    /**
     * Muestra un comentario específico.
     */
    public function show($id)
    {
        $comment = TaskComment::with(['task', 'user'])->findOrFail($id);
        return response()->json($comment);
    }

    /**
     * Crea un nuevo comentario.
     */
    public function store(Request $request)
    {
        // Validar datos
        $validatedData = $request->validate([
            'task_id'  => 'required|exists:tasks,id',
            'user_id'  => 'required|exists:users,id',
            'content'  => 'required|string',
        ]);

        // Crear el comentario
        $comment = TaskComment::create($validatedData);

        return response()->json($comment, 201);
    }

    /**
     * Actualiza un comentario existente.
     */
    public function update(Request $request, $id)
    {
        $comment = TaskComment::findOrFail($id);

        // Validar datos
        $validatedData = $request->validate([
            'content' => 'sometimes|required|string',
        ]);

        // Actualizar el comentario
        $comment->update($validatedData);

        return response()->json($comment);
    }

    /**
     * Elimina un comentario.
     */
    public function destroy($id)
    {
        $comment = TaskComment::findOrFail($id);
        $comment->delete();

        return response()->json([
            'message' => 'Comentario eliminado correctamente.'
        ], 200);
    }
}
