<?php

namespace Tests\Unit\Services;

use App\Models\Services\Plan;
use App\Services\Services\PlanImporterService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PlanImporterServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_validate_and_import_plans_csv_successfully()
    {
        $csvContent = "id,name,description,download_speed,upload_speed,monthly_price,status,modality_type,plan_type,is_dedicated,is_synchronized,data_limit,unlimited_data\n" .
            "1,Plan Fibra 50M,Plan 50 megas,50,25,105000,active,postpaid,internet,0,0,,0\n" .
            ",Plan Empresa 500M,Plan 500 megas dedicado,500,500,3900000,active,postpaid,internet,1,0,,0\n";

        $tempPath = sys_get_temp_dir() . '/test_plans_import_' . uniqid() . '.csv';
        file_put_contents($tempPath, $csvContent);

        try {
            $importer = new PlanImporterService();

            // 1. Pre-validation
            $validation = $importer->validateCsv($tempPath, 'create_or_update');
            $this->assertTrue($validation['valid']);
            $this->assertEquals(2, $validation['summary']['total_rows']);
            $this->assertEquals(2, $validation['summary']['created']);

            // 2. Import Execution
            $import = $importer->importCsv($tempPath, 'create_or_update');
            $this->assertTrue($import['success']);
            $this->assertEquals(2, $import['stats']['created']);
            $this->assertEmpty($import['errors']);

            $this->assertDatabaseHas('plans', [
                'name' => 'Plan Fibra 50M',
                'monthly_price' => 105000.00,
                'modality_type' => 'postpaid',
            ]);

            $this->assertDatabaseHas('plans', [
                'name' => 'Plan Empresa 500M',
                'monthly_price' => 3900000.00,
                'modality_type' => 'postpaid',
            ]);
        } finally {
            if (file_exists($tempPath)) {
                @unlink($tempPath);
            }
        }
    }
}
