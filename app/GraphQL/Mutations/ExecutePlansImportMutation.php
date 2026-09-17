<?php

namespace App\GraphQL\Mutations;

use App\Services\Services\PlanImporterService;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class ExecutePlansImportMutation
{
    public function resolve($root, array $args): array
    {
        $fileBase64 = $args['file_base64'] ?? null;
        $mode = $args['mode'] ?? 'create_or_update';

        if (empty($fileBase64)) {
            throw ValidationException::withMessages([
                'file_base64' => ['El archivo base64 es requerido para la importación.']
            ]);
        }

        $csvContent = base64_decode($fileBase64, true);
        if ($csvContent === false) {
            throw ValidationException::withMessages([
                'file_base64' => ['El contenido en base64 no es válido.']
            ]);
        }

        $tempPath = sys_get_temp_dir() . '/plans_imp_' . Str::random(10) . '.csv';
        file_put_contents($tempPath, $csvContent);

        try {
            $importer = new PlanImporterService();
            $result = $importer->importCsv($tempPath, $mode);
        } finally {
            if (file_exists($tempPath)) {
                @unlink($tempPath);
            }
        }

        return [
            'success' => $result['success'],
            'message' => $result['message'],
            'stats' => $result['stats'],
            'created_records' => $result['created_records'],
            'updated_records' => $result['updated_records'],
            'errors' => $result['errors'],
        ];
    }
}
