<?php

namespace Tests\Feature;

use App\Models\Inventory\EquipmentAssignment;
use App\Models\Inventory\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EquipmentAssignmentApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Create test data
        $this->user = User::factory()->create();
        $this->product = Product::factory()->create([
            'name' => 'Test Laptop',
            'sku' => 'TEST-001',
            'brand' => 'Test Brand',
            'description' => 'Test equipment for technician'
        ]);

        // Create equipment assignment for the user
        $this->equipmentAssignment = EquipmentAssignment::factory()->create([
            'user_id' => $this->user->id,
            'product_id' => $this->product->id,
            'status' => 'assigned',
            'condition_on_assignment' => 'good',
            'assigned_at' => now(),
            'notes' => 'Test assignment'
        ]);
    }

    public function test_get_equipment_assignments_requires_authentication(): void
    {
        $response = $this->getJson('/api/v1/app-movil/equipment-assignments');

        $response->assertStatus(401);
    }

    public function test_get_equipment_assignments_returns_user_assignments(): void
    {
        $response = $this->actingAs($this->user, 'api')
                         ->getJson('/api/v1/app-movil/equipment-assignments');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'success',
                     'data' => [
                         'equipment_assignments' => [
                             '*' => [
                                 'id',
                                 'user_id',
                                 'product_id',
                                 'assigned_at',
                                 'status',
                                 'condition_on_assignment',
                                 'notes',
                                 'product' => [
                                     'id',
                                     'name',
                                     'sku',
                                     'brand',
                                     'description'
                                 ]
                             ]
                         ]
                     ]
                 ])
                 ->assertJson([
                     'success' => true,
                     'data' => [
                         'equipment_assignments' => [
                             [
                                 'id' => $this->equipmentAssignment->id,
                                 'user_id' => $this->user->id,
                                 'product_id' => $this->product->id,
                                 'status' => 'assigned',
                                 'condition_on_assignment' => 'good',
                                 'notes' => 'Test assignment',
                                 'product' => [
                                     'id' => $this->product->id,
                                     'name' => 'Test Laptop',
                                     'sku' => 'TEST-001',
                                     'brand' => 'Test Brand',
                                     'description' => 'Test equipment for technician'
                                 ]
                             ]
                         ]
                     ]
                 ]);
    }

    public function test_get_equipment_assignments_returns_empty_for_user_without_assignments(): void
    {
        // Create a user with no equipment assignments
        $userWithoutEquipment = User::factory()->create();

        $response = $this->actingAs($userWithoutEquipment, 'api')
                         ->getJson('/api/v1/app-movil/equipment-assignments');

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'data' => [
                         'equipment_assignments' => []
                     ]
                 ]);
    }

    public function test_get_equipment_assignments_only_returns_authenticated_user_assignments(): void
    {
        // Create another user with equipment assignment
        $otherUser = User::factory()->create();
        $otherProduct = Product::factory()->create([
            'name' => 'Other Equipment',
            'sku' => 'OTHER-001'
        ]);

        EquipmentAssignment::factory()->create([
            'user_id' => $otherUser->id,
            'product_id' => $otherProduct->id,
            'status' => 'assigned'
        ]);

        // Request as first user should only return their assignments
        $response = $this->actingAs($this->user, 'api')
                         ->getJson('/api/v1/app-movil/equipment-assignments');

        $response->assertStatus(200)
                 ->assertJsonCount(1, 'data.equipment_assignments')
                 ->assertJson([
                     'success' => true,
                     'data' => [
                         'equipment_assignments' => [
                             [
                                 'user_id' => $this->user->id,
                                 'product' => [
                                     'name' => 'Test Laptop'
                                 ]
                             ]
                         ]
                     ]
                 ]);
    }

    public function test_get_equipment_assignments_includes_product_relationship(): void
    {
        $response = $this->actingAs($this->user, 'api')
                         ->getJson('/api/v1/app-movil/equipment-assignments');

        $response->assertStatus(200);

        $responseData = $response->json();
        $assignment = $responseData['data']['equipment_assignments'][0];

        // Verify product relationship is loaded
        $this->assertArrayHasKey('product', $assignment);
        $this->assertNotNull($assignment['product']);
        $this->assertEquals($this->product->id, $assignment['product']['id']);
        $this->assertEquals('Test Laptop', $assignment['product']['name']);
    }
}
