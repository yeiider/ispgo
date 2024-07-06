<?php

namespace Database\Factories;

use App\Models\Router;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class RouterFactory extends Factory
{
    protected $model = Router::class;

    public function definition()
    {
        return [
            'code' => "default",
            'name' => "Router Default",
            'status' => "enabled",
        ];
    }
}
