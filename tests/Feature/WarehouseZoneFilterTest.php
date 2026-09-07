<?php

namespace Tests\Feature;

use App\Models\Inventory\Product;
use App\Models\Inventory\ProductStock;
use App\Models\Inventory\Warehouse;
use App\Models\Router;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WarehouseZoneFilterTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_with_assigned_router_only_sees_warehouses_in_that_router()
    {
        $router1 = Router::factory()->create(['name' => 'Zone North', 'code' => 'ZN-01']);
        $router2 = Router::factory()->create(['name' => 'Zone South', 'code' => 'ZS-02']);

        $warehouse1 = Warehouse::create(['name' => 'North Warehouse', 'code' => 'WH-01', 'address' => 'Address 1', 'router_id' => $router1->id]);
        $warehouse2 = Warehouse::create(['name' => 'South Warehouse', 'code' => 'WH-02', 'address' => 'Address 2', 'router_id' => $router2->id]);
        $warehouse3 = Warehouse::create(['name' => 'Global Warehouse', 'code' => 'WH-03', 'address' => 'Address 3', 'router_id' => null]);

        $user = User::factory()->create();
        $user->routers()->attach($router1->id);

        $this->actingAs($user);

        $warehouses = Warehouse::all();

        $this->assertTrue($warehouses->contains('id', $warehouse1->id));
        $this->assertFalse($warehouses->contains('id', $warehouse2->id));
    }

    public function test_admin_user_without_router_restrictions_sees_all_warehouses()
    {
        $router1 = Router::factory()->create(['name' => 'Zone North 2', 'code' => 'ZN-02']);
        $router2 = Router::factory()->create(['name' => 'Zone South 2', 'code' => 'ZS-03']);

        $warehouse1 = Warehouse::create(['name' => 'North Warehouse 2', 'code' => 'WH-04', 'address' => 'Address 4', 'router_id' => $router1->id]);
        $warehouse2 = Warehouse::create(['name' => 'South Warehouse 2', 'code' => 'WH-05', 'address' => 'Address 5', 'router_id' => $router2->id]);

        $admin = User::factory()->create();

        $this->actingAs($admin);

        $warehouses = Warehouse::all();

        $this->assertCount(2, $warehouses);
        $this->assertTrue($warehouses->contains('id', $warehouse1->id));
        $this->assertTrue($warehouses->contains('id', $warehouse2->id));
    }

    public function test_product_stock_is_filtered_by_user_assigned_router()
    {
        $router1 = Router::factory()->create(['name' => 'Zone North 3', 'code' => 'ZN-03']);
        $router2 = Router::factory()->create(['name' => 'Zone South 3', 'code' => 'ZS-04']);

        $warehouse1 = Warehouse::create(['name' => 'North Warehouse 3', 'code' => 'WH-06', 'address' => 'Address 6', 'router_id' => $router1->id]);
        $warehouse2 = Warehouse::create(['name' => 'South Warehouse 3', 'code' => 'WH-07', 'address' => 'Address 7', 'router_id' => $router2->id]);

        $category = \App\Models\Inventory\Category::create(['name' => 'General', 'url_key' => 'general']);

        $product = Product::create([
            'name' => 'Test Product',
            'sku' => 'PROD-001',
            'price' => 100,
            'cost_price' => 50,
            'url_key' => 'test-product',
            'category_id' => $category->id,
            'status' => true,
        ]);

        $stock1 = ProductStock::create(['product_id' => $product->id, 'warehouse_id' => $warehouse1->id, 'quantity' => 10]);
        $stock2 = ProductStock::create(['product_id' => $product->id, 'warehouse_id' => $warehouse2->id, 'quantity' => 20]);

        $user = User::factory()->create();
        $user->routers()->attach($router1->id);

        $this->actingAs($user);

        $stocks = ProductStock::all();

        $this->assertCount(1, $stocks);
        $this->assertEquals($stock1->id, $stocks->first()->id);
    }
}
