<?php

namespace App\GraphQL\Mutations;

use App\Services\Services\PlanExporterService;

class ExportPlansCsvMutation
{
    public function export($root, array $args): array
    {
        $exporter = new PlanExporterService();
        $filters = $args['filters'] ?? [];
        $csvContent = $exporter->exportCsv($filters);

        return [
            'success' => true,
            'filename' => 'planes_export_' . date('Y-m-d_H-i-s') . '.csv',
            'content_base64' => base64_encode($csvContent)
        ];
    }

    public function downloadTemplate($root, array $args): array
    {
        $exporter = new PlanExporterService();
        $csvContent = $exporter->generateTemplateCsv();

        return [
            'success' => true,
            'filename' => 'plantilla_importacion_planes.csv',
            'content_base64' => base64_encode($csvContent)
        ];
    }
}
