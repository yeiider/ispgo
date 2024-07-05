<?php

namespace Database\Factories;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CustomerFactory extends Factory
{
    protected $model = Customer::class;

    public function definition()
    {
        return [
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'date_of_birth' => $this->faker->date,
            'phone_number' => $this->faker->numerify('###########'),
            'email_address' => $this->faker->unique()->safeEmail,
            'document_type' => $this->faker->randomElement(['DNI', 'PAS', 'CE']),
            'identity_document' => $this->faker->unique()->numerify('##########'),
            'customer_status' => $this->faker->randomElement(['active', 'inactive', 'suspended']),
            'additional_notes' => $this->faker->text,
        ];
    }
}
