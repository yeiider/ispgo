<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SupportTickets\TaskAttachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class TaskAttachmentController extends Controller
{
    /**
     * Lista todos los adjuntos, opcionalmente filtra por tarea.
     */
    public function index(Request $request)
    {
        // Si quieres filtrar por tarea, usa ?task_id=XYZ
        $query = TaskAttachment::query();

        if ($request->has('task_id')) {
            $query->where('task_id', $request->task_id);
        }

        // Carga de relaciones
        $attachments = $query->with(['task', 'uploader'])->get();

        return response()->json($attachments);
    }

    /**
     * Muestra un adjunto específico.
     */
    public function show($id)
    {
        $attachment = TaskAttachment::with(['task', 'uploader'])->findOrFail($id);
        return response()->json($attachment);
    }

    /**
     * Crea un nuevo adjunto.
     */
    public function store(Request $request)
    {
        // Validación básica
        $validatedData = $request->validate([
            'task_id'    => 'required|exists:tasks,id',
            'uploaded_by'=> 'required|exists:users,id',
            // Si subes archivos, puedes hacerlo así:
            'file'       => 'required|file|mimes:jpg,jpeg,png,pdf,zip|max:2048',
        ]);

        // Manejar la subida de archivo
        // Ajusta el path y disco según tu configuración (public, s3, etc.)
        $filePath = $request->file('file')->store('attachments', 'public');

        // Crear el registro en BDD
        $attachment = TaskAttachment::create([
            'task_id'    => $validatedData['task_id'],
            'uploaded_by'=> $validatedData['uploaded_by'],
            'file_path'  => $filePath,
            // Puedes generar un nombre real del archivo o usar un alias
            'file_name'  => $request->file('file')->getClientOriginalName(),
        ]);

        return response()->json($attachment, 201);
    }

    /**
     * Actualiza un adjunto (principalmente para cambiar nombre u otro campo).
     */
    public function update(Request $request, $id)
    {
        $attachment = TaskAttachment::findOrFail($id);

        // Por ejemplo, si a veces deseas cambiar el nombre
        $validatedData = $request->validate([
            'file_name' => 'sometimes|string|max:255',
        ]);

        $attachment->update($validatedData);

        return response()->json($attachment);
    }

    /**
     * Elimina un adjunto.
     */
    public function destroy($id)
    {
        $attachment = TaskAttachment::findOrFail($id);

        // Opcionalmente, eliminar el archivo físico
        if ($attachment->file_path) {
            Storage::disk('public')->delete($attachment->file_path);
        }

        $attachment->delete();

        return response()->json([
            'message' => 'Adjunto eliminado correctamente.'
        ], 200);
    }
}
