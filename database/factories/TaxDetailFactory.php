<?php

namespace Database\Factories;

use App\Models\Customers\Customer;
use App\Models\Customers\TaxDetail;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaxDetailFactory extends Factory
{
    protected $model = TaxDetail::class;

    public function definition()
    {
        return [
            'customer_id' => Customer::factory(),
            'tax_identification_type' => $this->faker->randomElement(['RFC', 'NIT', 'RUC']),
            'tax_identification_number' => $this->faker->unique()->numerify('##########'),
            'taxpayer_type' => $this->faker->randomElement(['individual', 'company']),
            'fiscal_regime' => $this->faker->randomElement(['general', 'simplified']),
            'business_name' => $this->faker->name(),
            'enable_billing' => $this->faker->boolean(),
            'send_notifications' => $this->faker->boolean(),
            'send_invoice' => $this->faker->boolean(),

        ];
    }
}
