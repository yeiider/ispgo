<?php

namespace Database\Factories\Services;

use App\Models\Services\Plan;
use Illuminate\Database\Eloquent\Factories\Factory;

class PlanFactory extends Factory
{
    protected $model = Plan::class;

    public function definition()
    {
        return [
            'name' => $this->faker->words(3, true),
            'description' => $this->faker->paragraph,
            'download_speed' => $this->faker->numberBetween(10, 1000), // in Mbps
            'upload_speed' => $this->faker->numberBetween(5, 500), // in Mbps
            'monthly_price' => $this->faker->randomFloat(2, 10, 200),
            'data_limit' => $this->faker->optional()->numberBetween(50, 1000), // in GB
            'unlimited_data' => $this->faker->boolean,
            'contract_period' => $this->faker->optional()->randomElement(['6 months', '12 months', '24 months']),
            'promotions' => $this->faker->optional()->text,
            'extras_included' => $this->faker->optional()->text,
            'geographic_availability' => $this->faker->optional()->text,
            'promotion_start_date' => $this->faker->optional()->date,
            'promotion_end_date' => $this->faker->optional()->date,
            'plan_image' => $this->faker->optional()->imageUrl,
            'customer_rating' => $this->faker->optional()->randomFloat(1, 1, 5),
            'customer_reviews' => $this->faker->optional()->text,
            'service_compatibility' => $this->faker->optional()->text,
            'network_priority' => $this->faker->optional()->randomElement(['high', 'medium', 'low']),
            'technical_support' => $this->faker->optional()->text,
            'additional_benefits' => $this->faker->optional()->text,
            'plan_type' => $this->faker->randomElement(['internet', 'television', 'telephonic']),
            'modality_type' => $this->faker->randomElement(['prepaid', 'postpaid']),
            'connection_type' => $this->faker->randomElement(['Fiber Optic', 'ADSL', 'Satellite']),

            'status' => $this->faker->randomElement(['active', 'inactive']),
        ];
    }
}
