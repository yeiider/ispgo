<?php

namespace Database\Factories\Customers;

use App\Models\Customers\Address;
use App\Models\Customers\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

class AddressFactory extends Factory
{
    protected $model = Address::class;

    public function definition()
    {
        return [
            'customer_id' => Customer::factory(),
            'address' => $this->faker->streetAddress,
            'city' => $this->faker->city,
            'state_province' => $this->faker->state,
            'postal_code' => $this->faker->postcode,
            'country' => $this->faker->country,
            'address_type' => $this->faker->randomElement(['billing', 'shipping']),
            'latitude' => $this->faker->latitude,
            'longitude' => $this->faker->longitude,
        ];
    }
}
