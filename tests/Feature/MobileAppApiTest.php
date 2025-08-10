<?php

namespace Tests\Feature;

use App\Models\Ticket;
use App\Models\User;
use App\Models\Customers\Customer;
use App\Models\Services\Service;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MobileAppApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Create test data
        $this->user = User::factory()->create();
        $this->customer = Customer::factory()->create();
        $this->service = Service::factory()->create();

        // Create a ticket and assign it to the user
        $this->ticket = Ticket::factory()->create([
            'customer_id' => $this->customer->id,
            'service_id' => $this->service->id,
        ]);

        $this->ticket->users()->attach($this->user->id);
    }

    public function test_get_tickets_data_requires_authentication(): void
    {
        $response = $this->getJson('/api/v1/app-movil/tickets-data');

        $response->assertStatus(401);
    }

    public function test_get_tickets_data_returns_services_and_customers(): void
    {
        $response = $this->actingAs($this->user, 'api')
                         ->getJson('/api/v1/app-movil/tickets-data');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'success',
                     'data' => [
                         'services' => [
                             '*' => ['id']
                         ],
                         'customers' => [
                             '*' => ['id']
                         ]
                     ]
                 ])
                 ->assertJson([
                     'success' => true
                 ]);
    }

    public function test_get_services_requires_authentication(): void
    {
        $response = $this->getJson('/api/v1/app-movil/services');

        $response->assertStatus(401);
    }

    public function test_get_services_returns_services_only(): void
    {
        $response = $this->actingAs($this->user, 'api')
                         ->getJson('/api/v1/app-movil/services');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'success',
                     'data' => [
                         'services' => [
                             '*' => ['id']
                         ]
                     ]
                 ])
                 ->assertJson([
                     'success' => true
                 ]);
    }

    public function test_get_customers_requires_authentication(): void
    {
        $response = $this->getJson('/api/v1/app-movil/customers');

        $response->assertStatus(401);
    }

    public function test_get_customers_returns_customers_only(): void
    {
        $response = $this->actingAs($this->user, 'api')
                         ->getJson('/api/v1/app-movil/customers');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'success',
                     'data' => [
                         'customers' => [
                             '*' => ['id']
                         ]
                     ]
                 ])
                 ->assertJson([
                     'success' => true
                 ]);
    }

    public function test_endpoints_return_empty_arrays_when_no_tickets_assigned(): void
    {
        // Create a user with no assigned tickets
        $userWithoutTickets = User::factory()->create();

        $response = $this->actingAs($userWithoutTickets, 'api')
                         ->getJson('/api/v1/app-movil/tickets-data');

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'data' => [
                         'services' => [],
                         'customers' => []
                     ]
                 ]);
    }
}
