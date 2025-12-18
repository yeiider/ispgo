<?php

namespace App\GraphQL\Mutations;

use App\Http\Controllers\API\FileUploadController;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

/**
 * Mutación GraphQL para manejo de archivos con el patrón Two-Step Upload.
 * 
 * Este patrón permite:
 * 1. Cargar archivos a una carpeta temporal para previsualización
 * 2. Mover archivos a ubicación permanente al confirmar el formulario
 * 3. Limpieza automática de archivos abandonados via S3 Lifecycle Policies
 */
class FileUploadMutation
{
    private const TEMP_FOLDER = 'tmp';
    
    /**
     * Disco de almacenamiento (siempre S3).
     */
    private string $disk = 's3';

    /**
     * Tiempo de expiración para URLs firmadas (en minutos).
     */
    private int $signedUrlExpiration = 60;

    /**
     * Sube un archivo temporal a S3.
     * 
     * Este método recibe un archivo en base64 y lo almacena en la carpeta temporal.
     * Es útil para clientes GraphQL que no pueden usar multipart/form-data.
     * 
     * @param mixed $root
     * @param array{input: array{file_base64: string, file_name: string, mime_type?: string, folder?: string}} $args
     * @return array
     */
    public function uploadTempBase64($root, array $args): array
    {
        $input = $args['input'];
        
        // Validar input
        if (empty($input['file_base64']) || empty($input['file_name'])) {
            throw ValidationException::withMessages([
                'input' => ['file_base64 y file_name son requeridos.']
            ]);
        }

        // Decodificar base64
        $fileData = base64_decode($input['file_base64'], true);
        if ($fileData === false) {
            throw ValidationException::withMessages([
                'file_base64' => ['El archivo base64 no es válido.']
            ]);
        }

        // Validar tamaño (5MB máximo)
        $sizeInBytes = strlen($fileData);
        if ($sizeInBytes > 5 * 1024 * 1024) {
            throw ValidationException::withMessages([
                'file_base64' => ['El archivo no puede exceder 5MB.']
            ]);
        }

        // Validar extensión
        $extension = strtolower(pathinfo($input['file_name'], PATHINFO_EXTENSION));
        $allowedExtensions = ['jpeg', 'jpg', 'png', 'gif', 'webp', 'svg'];
        if (!in_array($extension, $allowedExtensions)) {
            throw ValidationException::withMessages([
                'file_name' => ['El archivo debe ser de tipo: ' . implode(', ', $allowedExtensions)]
            ]);
        }

        // Generar nombre único
        $uniqueName = Str::uuid() . '_' . time() . '.' . $extension;

        // Construir path temporal
        $subfolder = $input['folder'] ?? '';
        $tempPath = self::TEMP_FOLDER;
        if (!empty($subfolder)) {
            $tempPath .= '/' . trim($subfolder, '/');
        }
        $fullPath = $tempPath . '/' . $uniqueName;

        try {
            // Almacenar en S3 SIN ACL (buckets modernos no permiten ACLs)
            // La visibilidad pública se maneja via política del bucket
            $stored = Storage::disk($this->disk)->put($fullPath, $fileData);

            if (!$stored) {
                Log::error('FileUploadMutation: Storage::put retornó false', [
                    'disk' => $this->disk,
                    'path' => $fullPath,
                ]);
                
                return [
                    'success' => false,
                    'message' => 'Error al cargar el archivo a S3.',
                    'preview_url' => null,
                    'temp_path' => null,
                ];
            }

            // Verificar que el archivo existe
            if (!Storage::disk($this->disk)->exists($fullPath)) {
                Log::error('FileUploadMutation: Archivo no existe después de put()', [
                    'disk' => $this->disk,
                    'path' => $fullPath,
                ]);
                
                return [
                    'success' => false,
                    'message' => 'El archivo no se almacenó correctamente en S3.',
                    'preview_url' => null,
                    'temp_path' => null,
                ];
            }

            $url = $this->getFileUrl($fullPath);

            Log::info('FileUploadMutation: Archivo cargado exitosamente', [
                'disk' => $this->disk,
                'path' => $fullPath,
                'url' => $url,
                'size' => $sizeInBytes,
            ]);

            return [
                'success' => true,
                'message' => 'Archivo cargado exitosamente.',
                'preview_url' => $url,
                'temp_path' => $fullPath,
                'original_name' => $input['file_name'],
                'mime_type' => $input['mime_type'] ?? $this->getMimeType($extension),
                'size' => $sizeInBytes,
            ];

        } catch (\Exception $e) {
            Log::error('FileUploadMutation: Excepción al cargar archivo', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'disk' => $this->disk,
                'path' => $fullPath,
            ]);
            
            return [
                'success' => false,
                'message' => 'Error al cargar el archivo: ' . $e->getMessage(),
                'preview_url' => null,
                'temp_path' => null,
            ];
        }
    }

    /**
     * Confirma y mueve un archivo temporal a ubicación permanente.
     * 
     * @param mixed $root
     * @param array{temp_path: string, destination_folder: string, new_file_name?: string} $args
     * @return array
     */
    public function confirmUpload($root, array $args): array
    {
        $tempPath = $args['temp_path'];
        $destinationFolder = $args['destination_folder'];
        $newFileName = $args['new_file_name'] ?? null;

        // Validar que el path es temporal
        if (!str_starts_with($tempPath, self::TEMP_FOLDER . '/')) {
            Log::warning('FileUploadMutation: Intento de mover archivo no temporal', [
                'path' => $tempPath,
            ]);
            
            return [
                'success' => false,
                'message' => 'El path proporcionado no corresponde a un archivo temporal válido.',
                'permanent_path' => null,
                'url' => null,
            ];
        }

        try {
            // Verificar que el archivo existe
            if (!Storage::disk($this->disk)->exists($tempPath)) {
                Log::warning('FileUploadMutation: Archivo temporal no encontrado', [
                    'path' => $tempPath,
                    'disk' => $this->disk,
                ]);
                
                return [
                    'success' => false,
                    'message' => 'El archivo temporal no existe o ya fue procesado.',
                    'permanent_path' => null,
                    'url' => null,
                ];
            }

            // Obtener nombre del archivo
            $fileName = $newFileName ?? basename($tempPath);
            $destinationPath = trim($destinationFolder, '/') . '/' . $fileName;

            // Copiar a ubicación permanente
            $copied = Storage::disk($this->disk)->copy($tempPath, $destinationPath);

            if (!$copied) {
                Log::error('FileUploadMutation: Error al copiar archivo', [
                    'from' => $tempPath,
                    'to' => $destinationPath,
                    'disk' => $this->disk,
                ]);
                
                return [
                    'success' => false,
                    'message' => 'Error al mover el archivo a la ubicación permanente.',
                    'permanent_path' => null,
                    'url' => null,
                ];
            }

            // Nota: No usamos setVisibility() porque el bucket no permite ACLs
            // La visibilidad pública se maneja via política del bucket en AWS

            // Eliminar temporal
            Storage::disk($this->disk)->delete($tempPath);

            $url = $this->getFileUrl($destinationPath);

            Log::info('FileUploadMutation: Archivo movido a ubicación permanente', [
                'from' => $tempPath,
                'to' => $destinationPath,
                'url' => $url,
            ]);

            return [
                'success' => true,
                'message' => 'Archivo confirmado exitosamente.',
                'permanent_path' => $destinationPath,
                'url' => $url,
            ];

        } catch (\Exception $e) {
            Log::error('FileUploadMutation: Excepción al mover archivo', [
                'error' => $e->getMessage(),
                'from' => $tempPath,
                'destination_folder' => $destinationFolder,
            ]);
            
            return [
                'success' => false,
                'message' => 'Error al mover el archivo: ' . $e->getMessage(),
                'permanent_path' => null,
                'url' => null,
            ];
        }
    }

    /**
     * Elimina un archivo temporal.
     * 
     * @param mixed $root
     * @param array{temp_path: string} $args
     * @return array
     */
    public function deleteTempFile($root, array $args): array
    {
        $tempPath = $args['temp_path'];

        // Validar que es un archivo temporal
        if (!str_starts_with($tempPath, self::TEMP_FOLDER . '/')) {
            return [
                'success' => false,
                'message' => 'Solo se pueden eliminar archivos temporales.',
            ];
        }

        $deleted = Storage::disk($this->disk)->delete($tempPath);

        return [
            'success' => $deleted,
            'message' => $deleted ? 'Archivo temporal eliminado.' : 'El archivo no existe o ya fue eliminado.',
        ];
    }

    /**
     * Método helper para mover archivo temporal a permanente.
     * Puede ser usado por otras mutaciones internamente.
     * 
     * @param string $tempPath
     * @param string $destinationFolder
     * @param string|null $newFileName
     * @return array{success: bool, permanent_path?: string, url?: string, message?: string}
     */
    public static function moveToPermanentStorage(string $tempPath, string $destinationFolder, ?string $newFileName = null): array
    {
        $instance = new self();
        return $instance->confirmUpload(null, [
            'temp_path' => $tempPath,
            'destination_folder' => $destinationFolder,
            'new_file_name' => $newFileName,
        ]);
    }

    /**
     * Valida si un path temporal existe y es válido.
     */
    public static function validateTempPath(string $tempPath): bool
    {
        if (!str_starts_with($tempPath, self::TEMP_FOLDER . '/')) {
            return false;
        }

        $disk = config('filesystems.default', 's3');
        return Storage::disk($disk)->exists($tempPath);
    }

    /**
     * Obtiene el mime type basado en la extensión.
     */
    private function getMimeType(string $extension): string
    {
        return match ($extension) {
            'jpeg', 'jpg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
            'webp' => 'image/webp',
            'svg' => 'image/svg+xml',
            default => 'application/octet-stream',
        };
    }

    /**
     * Genera la URL del archivo.
     * 
     * Si USE_SIGNED_URLS está habilitado, genera una URL firmada (presigned) con expiración.
     * De lo contrario, genera una URL pública simple.
     *
     * @param string $path Path del archivo en S3
     * @param int|null $expirationMinutes Minutos de expiración para URL firmada
     * @return string URL del archivo
     */
    private function getFileUrl(string $path, ?int $expirationMinutes = null): string
    {
        $useSignedUrls = config('filesystems.disks.s3.use_signed_urls', false);

        if ($useSignedUrls) {
            $expiration = now()->addMinutes($expirationMinutes ?? $this->signedUrlExpiration);
            return Storage::disk($this->disk)->temporaryUrl($path, $expiration);
        }

        return Storage::disk($this->disk)->url($path);
    }
}
