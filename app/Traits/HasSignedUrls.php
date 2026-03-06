<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

trait HasSignedUrls
{
    /**
     * Genera una URL firmada para un archivo almacenado en S3.
     *
     * @param string|null $path Ruta del archivo
     * @param string $disk Disco de almacenamiento (por defecto 's3')
     * @param int $expirationMinutes Minutos de expiración (por defecto 60)
     * @param string|null $logContext Contexto para logs (por defecto usa el nombre del modelo)
     * @return string|null
     */
    protected function generateSignedUrl(
        ?string $path,
        string $disk = 's3',
        int $expirationMinutes = 60,
        ?string $logContext = null
    ): ?string {
        if (empty($path)) {
            return null;
        }

        // Si ya es una URL completa, retornarla tal cual
        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        try {
            $useSignedUrls = config('filesystems.disks.' . $disk . '.use_signed_urls', true);

            if ($useSignedUrls) {
                // URL firmada con expiración personalizable
                return Storage::disk($disk)->temporaryUrl($path, now()->addMinutes($expirationMinutes));
            }

            return Storage::disk($disk)->url($path);
        } catch (\Exception $e) {
            // Si falla la generación de URL firmada, intentar URL simple
            Log::warning('Error generando URL firmada', [
                'context' => $logContext ?? static::class,
                'model_id' => $this->id ?? null,
                'path' => $path,
                'disk' => $disk,
                'error' => $e->getMessage(),
            ]);

            try {
                return Storage::disk($disk)->url($path);
            } catch (\Exception $e) {
                return null;
            }
        }
    }

    /**
     * Crea un accessor para una URL de imagen firmada.
     *
     * @param string $attributeName Nombre del atributo que contiene el path (por defecto 'image')
     * @param string $disk Disco de almacenamiento (por defecto 's3')
     * @param int $expirationMinutes Minutos de expiración (por defecto 60)
     * @return Attribute
     */
    protected function signedUrlAttribute(
        string $attributeName = 'image',
        string $disk = 's3',
        int $expirationMinutes = 60
    ): Attribute {
        return Attribute::make(
            get: fn() => $this->generateSignedUrl(
                $this->attributes[$attributeName] ?? null,
                $disk,
                $expirationMinutes,
                static::class
            )
        );
    }
}
