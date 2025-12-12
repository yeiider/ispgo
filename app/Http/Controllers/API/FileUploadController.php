<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

/**
 * @OA\Tag(
 *     name="File Upload",
 *     description="Endpoints para carga de archivos con patrón Two-Step Upload"
 * )
 */
class FileUploadController extends Controller
{
    /**
     * Carpeta temporal en S3 para archivos pendientes de confirmación.
     * Los archivos en esta carpeta serán eliminados automáticamente 
     * por las políticas de ciclo de vida de S3.
     */
    private const TEMP_FOLDER = 'tmp';

    /**
     * Disco de almacenamiento a utilizar.
     */
    private string $disk;

    public function __construct()
    {
        $this->disk = config('filesystems.default', 's3');
    }

    /**
     * Paso 1: Carga temporal de archivo.
     * 
     * Almacena el archivo en una carpeta temporal de S3 y retorna
     * la URL de previsualización junto con el path temporal.
     *
     * @OA\Post(
     *     path="/api/upload/temp",
     *     operationId="uploadTemp",
     *     tags={"File Upload"},
     *     summary="Carga temporal de imagen",
     *     description="Carga una imagen a almacenamiento temporal. La imagen será eliminada automáticamente si no se confirma.",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="file",
     *                     type="string",
     *                     format="binary",
     *                     description="Archivo de imagen (máx 5MB)"
     *                 ),
     *                 @OA\Property(
     *                     property="folder",
     *                     type="string",
     *                     description="Subcarpeta opcional dentro de tmp/"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Archivo cargado exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="preview_url", type="string", example="https://bucket.s3.amazonaws.com/tmp/abc123.jpg"),
     *             @OA\Property(property="temp_path", type="string", example="tmp/abc123.jpg"),
     *             @OA\Property(property="original_name", type="string", example="mi-imagen.jpg"),
     *             @OA\Property(property="mime_type", type="string", example="image/jpeg"),
     *             @OA\Property(property="size", type="integer", example=102400)
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error de validación"
     *     )
     * )
     */
    public function uploadTemp(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'file' => [
                'required',
                'file',
                'image',
                'max:5120', // 5MB en kilobytes
                'mimes:jpeg,jpg,png,gif,webp,svg',
            ],
            'folder' => 'nullable|string|max:100',
        ], [
            'file.required' => 'El archivo es requerido.',
            'file.image' => 'El archivo debe ser una imagen válida.',
            'file.max' => 'El archivo no puede exceder 5MB.',
            'file.mimes' => 'El archivo debe ser de tipo: jpeg, jpg, png, gif, webp o svg.',
        ]);

        $file = $request->file('file');
        $subfolder = $validated['folder'] ?? '';
        
        // Generar nombre único para evitar colisiones
        $uniqueName = $this->generateUniqueFileName($file->getClientOriginalExtension());
        
        // Construir path temporal
        $tempPath = self::TEMP_FOLDER;
        if (!empty($subfolder)) {
            $tempPath .= '/' . trim($subfolder, '/');
        }
        $fullPath = $tempPath . '/' . $uniqueName;

        // Almacenar en S3 con visibilidad pública para previsualización
        $storedPath = Storage::disk($this->disk)->putFileAs(
            $tempPath,
            $file,
            $uniqueName,
            'public'
        );

        if (!$storedPath) {
            return response()->json([
                'success' => false,
                'message' => 'Error al cargar el archivo. Por favor, intente nuevamente.',
            ], 500);
        }

        return response()->json([
            'success' => true,
            'preview_url' => Storage::disk($this->disk)->url($storedPath),
            'temp_path' => $storedPath,
            'original_name' => $file->getClientOriginalName(),
            'mime_type' => $file->getMimeType(),
            'size' => $file->getSize(),
        ]);
    }

    /**
     * Paso 2: Mover archivo de temporal a permanente.
     * 
     * Este método puede ser llamado directamente o utilizado internamente
     * por otros controladores/mutaciones.
     *
     * @OA\Post(
     *     path="/api/upload/confirm",
     *     operationId="confirmUpload",
     *     tags={"File Upload"},
     *     summary="Confirmar y mover archivo a ubicación permanente",
     *     description="Mueve un archivo desde la carpeta temporal a una carpeta permanente.",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="temp_path", type="string", example="tmp/abc123.jpg", description="Path temporal retornado por uploadTemp"),
     *             @OA\Property(property="destination_folder", type="string", example="products", description="Carpeta destino permanente")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Archivo movido exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="permanent_path", type="string", example="products/abc123.jpg"),
     *             @OA\Property(property="url", type="string", example="https://bucket.s3.amazonaws.com/products/abc123.jpg")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Archivo temporal no encontrado"
     *     )
     * )
     */
    public function confirmUpload(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'temp_path' => 'required|string',
            'destination_folder' => 'required|string|max:100',
        ]);

        $result = $this->moveToPermament(
            $validated['temp_path'],
            $validated['destination_folder']
        );

        if (!$result['success']) {
            return response()->json($result, 404);
        }

        return response()->json($result);
    }

    /**
     * Mueve un archivo de la carpeta temporal a una carpeta permanente.
     * 
     * Este método puede ser utilizado por otros controladores o mutaciones
     * para confirmar la carga de un archivo.
     *
     * @param string $tempPath Path del archivo en la carpeta temporal
     * @param string $destinationFolder Carpeta destino (ej: 'products', 'avatars')
     * @param string|null $newFileName Nombre personalizado para el archivo (opcional)
     * @return array{success: bool, permanent_path?: string, url?: string, message?: string}
     */
    public function moveToPermament(string $tempPath, string $destinationFolder, ?string $newFileName = null): array
    {
        // Validar que el path comienza con la carpeta temporal
        if (!str_starts_with($tempPath, self::TEMP_FOLDER . '/')) {
            return [
                'success' => false,
                'message' => 'El path proporcionado no corresponde a un archivo temporal válido.',
            ];
        }

        // Verificar que el archivo existe en S3
        if (!Storage::disk($this->disk)->exists($tempPath)) {
            return [
                'success' => false,
                'message' => 'El archivo temporal no existe o ya fue procesado. El archivo puede haber expirado.',
            ];
        }

        // Obtener el nombre del archivo
        $fileName = $newFileName ?? basename($tempPath);
        
        // Construir el path de destino
        $destinationPath = trim($destinationFolder, '/') . '/' . $fileName;

        // Copiar archivo a ubicación permanente
        $copied = Storage::disk($this->disk)->copy($tempPath, $destinationPath);

        if (!$copied) {
            return [
                'success' => false,
                'message' => 'Error al mover el archivo a la ubicación permanente.',
            ];
        }

        // Hacer el archivo público en la ubicación permanente
        Storage::disk($this->disk)->setVisibility($destinationPath, 'public');

        // Eliminar archivo temporal
        Storage::disk($this->disk)->delete($tempPath);

        return [
            'success' => true,
            'permanent_path' => $destinationPath,
            'url' => Storage::disk($this->disk)->url($destinationPath),
        ];
    }

    /**
     * Elimina un archivo temporal.
     * 
     * Útil si el usuario cancela el formulario y queremos limpiar el archivo
     * sin esperar a que la política de ciclo de vida de S3 lo elimine.
     *
     * @OA\Delete(
     *     path="/api/upload/temp",
     *     operationId="deleteTempFile",
     *     tags={"File Upload"},
     *     summary="Eliminar archivo temporal",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="temp_path", type="string", example="tmp/abc123.jpg")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Archivo eliminado"
     *     )
     * )
     */
    public function deleteTempFile(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'temp_path' => 'required|string',
        ]);

        $tempPath = $validated['temp_path'];

        // Validar que es un archivo temporal
        if (!str_starts_with($tempPath, self::TEMP_FOLDER . '/')) {
            return response()->json([
                'success' => false,
                'message' => 'Solo se pueden eliminar archivos temporales.',
            ], 400);
        }

        $deleted = Storage::disk($this->disk)->delete($tempPath);

        return response()->json([
            'success' => $deleted,
            'message' => $deleted ? 'Archivo temporal eliminado.' : 'El archivo no existe o ya fue eliminado.',
        ]);
    }

    /**
     * Genera un nombre de archivo único.
     */
    private function generateUniqueFileName(string $extension): string
    {
        return Str::uuid() . '_' . time() . '.' . strtolower($extension);
    }
}
