<?php

namespace Database\Seeders;

use App\Models\Services\Plan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PlanSeeder extends Seeder
{
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Plan::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        Plan::factory()->count(10)->create();
    }
}
