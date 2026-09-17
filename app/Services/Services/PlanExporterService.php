<?php

namespace App\Services\Services;

use App\Models\Services\Plan;

class PlanExporterService
{
    /**
     * Genera el contenido del archivo CSV con la lista de planes.
     */
    public function exportCsv(?array $filters = []): string
    {
        $query = Plan::query();

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }
        if (!empty($filters['plan_type'])) {
            $query->where('plan_type', $filters['plan_type']);
        }
        if (!empty($filters['modality_type'])) {
            $query->where('modality_type', $filters['modality_type']);
        }

        $plans = $query->orderBy('id', 'asc')->get();

        $headers = [
            'id',
            'name',
            'description',
            'download_speed',
            'upload_speed',
            'monthly_price',
            'status',
            'modality_type',
            'plan_type',
            'profile_smart_olt',
            'is_dedicated',
            'is_synchronized',
            'data_limit',
            'unlimited_data'
        ];

        $output = fopen('php://temp', 'r+');
        fputcsv($output, $headers);

        foreach ($plans as $plan) {
            fputcsv($output, [
                $plan->id,
                $plan->name,
                $plan->description,
                $plan->download_speed,
                $plan->upload_speed,
                $plan->monthly_price,
                $plan->status ?? 'active',
                $plan->modality_type ?? 'residential',
                $plan->plan_type ?? 'internet',
                $plan->profile_smart_olt,
                $plan->is_dedicated ? '1' : '0',
                $plan->is_synchronized ? '1' : '0',
                $plan->data_limit,
                $plan->unlimited_data ? '1' : '0'
            ]);
        }

        rewind($output);
        $csvContent = stream_get_contents($output);
        fclose($output);

        return $csvContent;
    }

    /**
     * Genera la plantilla CSV de ejemplo para importación de planes.
     */
    public function generateTemplateCsv(): string
    {
        $headers = [
            'id',
            'name',
            'description',
            'download_speed',
            'upload_speed',
            'monthly_price',
            'status',
            'modality_type',
            'plan_type',
            'profile_smart_olt',
            'is_dedicated',
            'is_synchronized',
            'data_limit',
            'unlimited_data'
        ];

        $sampleRow = [
            '', // id opcional si es nuevo
            'Plan Fibra 100 Megas',
            'Plan de fibra óptica 100M simétrico',
            '100',
            '100',
            '65000',
            'active',
            'residential',
            'internet',
            'PROFILE_100M',
            '0',
            '1',
            '',
            '1'
        ];

        $output = fopen('php://temp', 'r+');
        fputcsv($output, $headers);
        fputcsv($output, $sampleRow);

        rewind($output);
        $csvContent = stream_get_contents($output);
        fclose($output);

        return $csvContent;
    }
}
