<?php

namespace Tests\Feature;

use App\Models\BillingNovedad;
use App\Models\Customers\Customer;
use App\Models\Router;
use App\Models\Services\Plan;
use App\Models\Services\Service;
use App\Services\Billing\BillingNovedadImporterService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class ImportBillingNovedadesTest extends TestCase
{
    use DatabaseTransactions;

    public function test_import_billing_novedades_with_percentage_and_fixed_types()
    {
        /** @var \App\Models\User $user */
        $user = \App\Models\User::factory()->create();
        $this->actingAs($user);

        $router = Router::create([
            'code' => 'R_NOV_1',
            'name' => 'Router Novedades',
        ]);

        $customer = Customer::create([
            'first_name' => 'Novedad',
            'last_name' => 'TestUser',
            'email_address' => 'novedad@example.com',
            'phone_number' => '3000001111',
            'document_type' => 'CC',
            'identity_document' => '100200300',
            'customer_status' => 'active',
            'router_id' => $router->id,
        ]);

        $plan = Plan::create([
            'name' => 'Plan Fiber 100MB',
            'download_speed' => 100,
            'upload_speed' => 100,
            'monthly_price' => 100000,
        ]);

        $service = Service::create([
            'customer_id' => $customer->id,
            'router_id' => $router->id,
            'plan_id' => $plan->id,
            'service_ip' => '192.168.99.10',
            'service_status' => 'active',
        ]);

        $csvContent = "service_id,type,amount,description,effective_period,discount_type,discount_value,start_day,end_day,mora_type,mora_value\n";
        $csvContent .= "{$service->id},cargo_adicional,15000.00,Cargo soporte tecnico,2026-09-01,,,,,,\n";
        $csvContent .= "{$service->id},descuento_promocional,,Descuento 15% pronto pago,2026-09-01,percentage,15,,,,\n";
        $csvContent .= "{$service->id},descuento_promocional,,Descuento fijo 20k,2026-09-01,fixed,20000,,,,\n";
        $csvContent .= "{$service->id},prorrateo_inicial,,Prorrateo ingreso dia 10,2026-09-01,,,10,,,\n";

        $tempFile = tempnam(sys_get_temp_dir(), 'csv');
        file_put_contents($tempFile, $csvContent);

        $importer = new BillingNovedadImporterService();

        // 1. Dry run validation
        $valResult = $importer->validateCsv($tempFile);
        $this->assertTrue($valResult['valid'], 'La validación CSV debería ser exitosa. Errores: ' . json_encode($valResult['errors']));
        $this->assertEquals(4, $valResult['summary']['created']);

        // 2. Execution
        $impResult = $importer->importCsv($tempFile);
        $this->assertTrue($impResult['success'], 'La importación CSV debería completarse sin errores. Errores: ' . json_encode($impResult['errors']));
        $this->assertEquals(4, $impResult['stats']['created']);

        // 3. Assertions in Database
        $this->assertDatabaseHas('billing_novedades', [
            'service_id' => $service->id,
            'type' => 'cargo_adicional',
            'amount' => 15000.00,
        ]);

        $this->assertDatabaseHas('billing_novedades', [
            'service_id' => $service->id,
            'type' => 'descuento_promocional',
            'amount' => -15000.00, // 15% of 100,000 = -15,000
        ]);

        $this->assertDatabaseHas('billing_novedades', [
            'service_id' => $service->id,
            'type' => 'descuento_promocional',
            'amount' => -20000.00, // Fixed 20,000 = -20,000
        ]);

        @unlink($tempFile);
    }
}
