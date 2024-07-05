<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\InternetPlan;
use Illuminate\Support\Facades\DB;

class InternetPlanSeeder extends Seeder
{
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        InternetPlan::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        InternetPlan::factory()->count(10)->create();
    }
}
