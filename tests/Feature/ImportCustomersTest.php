<?php

namespace Tests\Feature;

use App\Models\Customers\Customer;
use App\Models\Customers\TaxDetail;
use App\Nova\Actions\Customers\ImportCustomers;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Laravel\Nova\Fields\ActionFields;
use Tests\TestCase;

class ImportCustomersTest extends TestCase
{
    use RefreshDatabase;

    public function test_import_customers_with_tax_details()
    {
        // Create router first to avoid foreign key violation
        $router = \App\Models\Router::create([
            'code' => 'R01',
            'name' => 'Test Router',
        ]);

        // Create a temporary CSV file content
        $csvContent = "customer.first_name,customer.last_name,customer.email_address,customer.phone_number,customer.document_type,customer.identity_document,customer.customer_status,customer.router_id,address.address,address.city,address.state_province,address.postal_code,address.country,address.address_type,tax.tax_identification_type,tax.tax_identification_number,tax.taxpayer_type,tax.fiscal_regime,tax.business_name,tax.enable_billing,tax.send_notifications,tax.send_invoice\n";
        $csvContent .= "TestFirst,TestLast,test@example.com,123456789,CC,987654321,active,{$router->id},Calle Falsa 123,Bogota,Cundinamarca,110111,CO,billing,NIT,123456789-0,personas_juridicas,general,Test Business Name,1,0,0\n";

        $tempFile = tempnam(sys_get_temp_dir(), 'csv');
        file_put_contents($tempFile, $csvContent);

        $uploadedFile = new UploadedFile(
            $tempFile,
            'import.csv',
            'text/csv',
            null,
            true
        );

        $action = new ImportCustomers();
        $fields = new ActionFields(
            collect([
                'csv_file' => $uploadedFile,
                'mode' => 'create_or_update',
            ]),
            collect()
        );

        // Act
        $response = $action->handle($fields, collect());

        // Assert
        $this->assertDatabaseHas('customers', [
            'first_name' => 'TestFirst',
            'last_name' => 'TestLast',
            'identity_document' => '987654321',
        ]);

        $customer = Customer::where('identity_document', '987654321')->first();
        $this->assertNotNull($customer);

        $this->assertDatabaseHas('tax_details', [
            'customer_id' => $customer->id,
            'tax_identification_type' => 'NIT',
            'tax_identification_number' => '123456789-0',
            'taxpayer_type' => 'personas_juridicas',
            'fiscal_regime' => 'general',
            'business_name' => 'Test Business Name',
            'enable_billing' => 1,
        ]);

        @unlink($tempFile);
    }

    public function test_import_customers_with_optional_billing_cycle()
    {
        $router = \App\Models\Router::create([
            'code' => 'R02',
            'name' => 'Test Router 2',
        ]);

        $plan = \App\Models\Services\Plan::create([
            'name' => 'Plan 10MB',
            'download_speed' => 10,
            'upload_speed' => 10,
            'monthly_price' => 50000,
        ]);

        $cycle = \App\Models\BillingCycle::create([
            'name' => 'Ciclo 15',
            'billing_day' => 15,
            'suspension_day' => 20,
            'payment_due_day' => 25,
            'status' => 'active',
        ]);

        $csvContent = "customer.first_name,customer.last_name,customer.email_address,customer.phone_number,customer.document_type,customer.identity_document,customer.customer_status,customer.router_id,service.router_id,service.plan_id,service.service_ip,service.service_status,service.billing_cycle_id\n";
        $csvContent .= "CicloUser,LastName,ciclo@example.com,3001112233,CC,555444333,active,{$router->id},{$router->id},{$plan->id},192.168.10.50,active,{$cycle->id}\n";

        $tempFile = tempnam(sys_get_temp_dir(), 'csv');
        file_put_contents($tempFile, $csvContent);

        $uploadedFile = new UploadedFile(
            $tempFile,
            'import.csv',
            'text/csv',
            null,
            true
        );

        $action = new ImportCustomers();
        $fields = new ActionFields(
            collect([
                'csv_file' => $uploadedFile,
                'mode' => 'create_or_update',
            ]),
            collect()
        );

        $action->handle($fields, collect());

        $this->assertDatabaseHas('services', [
            'service_ip' => '192.168.10.50',
            'billing_cycle_id' => $cycle->id,
            'billing_cycle' => 'Ciclo 15',
        ]);

        @unlink($tempFile);
    }
}
