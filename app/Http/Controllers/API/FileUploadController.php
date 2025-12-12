<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
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
     * Disco de almacenamiento a utilizar (siempre S3).
     */
    private string $disk = 's3';

    /**
     * Tiempo de expiración para URLs firmadas (en minutos).
     * Usado para previsualización de archivos temporales.
     */
    private int $signedUrlExpiration = 60; // 1 hora

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

        try {
            // Leer el contenido del archivo
            $fileContent = file_get_contents($file->getRealPath());
            
            if ($fileContent === false) {
                Log::error('FileUpload: No se pudo leer el archivo temporal', [
                    'original_name' => $file->getClientOriginalName(),
                    'path' => $file->getRealPath(),
                ]);
                
                return response()->json([
                    'success' => false,
                    'message' => 'Error al leer el archivo. Por favor, intente nuevamente.',
                ], 500);
            }

            // Almacenar en S3 SIN ACL (buckets modernos no permiten ACLs)
            // La visibilidad pública se maneja via política del bucket
            $stored = Storage::disk($this->disk)->put($fullPath, $fileContent);

            if (!$stored) {
                Log::error('FileUpload: Storage::put retornó false', [
                    'disk' => $this->disk,
                    'path' => $fullPath,
                ]);
                
                return response()->json([
                    'success' => false,
                    'message' => 'Error al cargar el archivo a S3. Por favor, intente nuevamente.',
                ], 500);
            }

            // Verificar que el archivo existe en S3
            if (!Storage::disk($this->disk)->exists($fullPath)) {
                Log::error('FileUpload: El archivo no existe después de put()', [
                    'disk' => $this->disk,
                    'path' => $fullPath,
                ]);
                
                return response()->json([
                    'success' => false,
                    'message' => 'El archivo no se almacenó correctamente en S3.',
                ], 500);
            }

            $url = $this->getFileUrl($fullPath);

            Log::info('FileUpload: Archivo cargado exitosamente', [
                'disk' => $this->disk,
                'path' => $fullPath,
                'url' => $url,
                'size' => $file->getSize(),
            ]);

            return response()->json([
                'success' => true,
                'preview_url' => $url,
                'temp_path' => $fullPath,
                'original_name' => $file->getClientOriginalName(),
                'mime_type' => $file->getMimeType(),
                'size' => $file->getSize(),
            ]);

        } catch (\Exception $e) {
            Log::error('FileUpload: Excepción al cargar archivo', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'disk' => $this->disk,
                'path' => $fullPath,
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Error al cargar el archivo: ' . $e->getMessage(),
            ], 500);
        }
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
            Log::warning('FileUpload: Intento de mover archivo no temporal', [
                'path' => $tempPath,
            ]);
            
            return [
                'success' => false,
                'message' => 'El path proporcionado no corresponde a un archivo temporal válido.',
            ];
        }

        try {
            // Verificar que el archivo existe en S3
            if (!Storage::disk($this->disk)->exists($tempPath)) {
                Log::warning('FileUpload: Archivo temporal no encontrado', [
                    'path' => $tempPath,
                    'disk' => $this->disk,
                ]);
                
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
                Log::error('FileUpload: Error al copiar archivo', [
                    'from' => $tempPath,
                    'to' => $destinationPath,
                    'disk' => $this->disk,
                ]);
                
                return [
                    'success' => false,
                    'message' => 'Error al mover el archivo a la ubicación permanente.',
                ];
            }

            // Nota: No usamos setVisibility() porque el bucket no permite ACLs
            // La visibilidad pública se maneja via política del bucket en AWS

            // Eliminar archivo temporal
            Storage::disk($this->disk)->delete($tempPath);

            $url = $this->getFileUrl($destinationPath);

            Log::info('FileUpload: Archivo movido a ubicación permanente', [
                'from' => $tempPath,
                'to' => $destinationPath,
                'url' => $url,
            ]);

            return [
                'success' => true,
                'permanent_path' => $destinationPath,
                'url' => $url,
            ];

        } catch (\Exception $e) {
            Log::error('FileUpload: Excepción al mover archivo', [
                'error' => $e->getMessage(),
                'from' => $tempPath,
                'destination_folder' => $destinationFolder,
            ]);
            
            return [
                'success' => false,
                'message' => 'Error al mover el archivo: ' . $e->getMessage(),
            ];
        }
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

    /**
     * Genera la URL del archivo.
     * 
     * Si USE_SIGNED_URLS está habilitado, genera una URL firmada (presigned) con expiración.
     * De lo contrario, genera una URL pública simple.
     *
     * @param string $path Path del archivo en S3
     * @param int|null $expirationMinutes Minutos de expiración para URL firmada (null = usar default)
     * @return string URL del archivo
     */
    public function getFileUrl(string $path, ?int $expirationMinutes = null): string
    {
        $useSignedUrls = config('filesystems.disks.s3.use_signed_urls', false);

        if ($useSignedUrls) {
            $expiration = now()->addMinutes($expirationMinutes ?? $this->signedUrlExpiration);
            return Storage::disk($this->disk)->temporaryUrl($path, $expiration);
        }

        return Storage::disk($this->disk)->url($path);
    }

    /**
     * Genera una URL firmada (presigned) para un archivo en S3.
     * 
     * Útil cuando necesitas dar acceso temporal a un archivo privado.
     *
     * @param string $path Path del archivo en S3
     * @param int $expirationMinutes Minutos de expiración (default: 60)
     * @return string URL firmada
     */
    public function getSignedUrl(string $path, int $expirationMinutes = 60): string
    {
        $expiration = now()->addMinutes($expirationMinutes);
        return Storage::disk($this->disk)->temporaryUrl($path, $expiration);
    }
}
