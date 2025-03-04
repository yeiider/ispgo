<?php

namespace Database\Factories\Services;

use App\Models\Customers\Address;
use App\Models\Customers\Customer;
use App\Models\Services\Plan;
use App\Models\Services\Service;
use Illuminate\Database\Eloquent\Factories\Factory;

class ServiceFactory extends Factory
{
    protected $model = Service::class;

    public function definition()
    {
        return [
            'router_id' => 1, // Asignar un router específico
            'customer_id' => Customer::factory(),
            'plan_id' => Plan::factory(),
            'service_ip' => $this->faker->ipv4,
            'username_router' => $this->faker->userName,
            'password_router' => $this->faker->password,
            'service_status' => $this->faker->randomElement(['active', 'inactive', 'suspended', 'pending', 'free']),
            'activation_date' => $this->faker->optional()->date,
            'deactivation_date' => $this->faker->optional()->date,
            'bandwidth' => $this->faker->optional()->numberBetween(10, 1000), // in Mbps
            'mac_address' => $this->faker->optional()->macAddress,
            'installation_date' => $this->faker->optional()->date,
            'service_notes' => $this->faker->optional()->text,
            'contract_id' => $this->faker->optional()->randomNumber(),
            'support_contact' => $this->faker->optional()->phoneNumber,
            'service_location' => null,
            'service_type' => $this->faker->optional()->randomElement(["ftth", "adsl", "satellite"]),
            'static_ip' => $this->faker->boolean,
            'data_limit' => $this->faker->optional()->numberBetween(50, 1000), // in GB
            'last_maintenance' => $this->faker->optional()->date,
            'billing_cycle' => $this->faker->optional()->randomElement(['Monthly', 'Bimonthly']),
            'service_priority' => $this->faker->randomElement(['normal', 'high', 'critical']),
            'assigned_technician' => $this->faker->optional()->randomNumber(),
            'service_contract' => $this->faker->optional()->text,
        ];
    }
}
