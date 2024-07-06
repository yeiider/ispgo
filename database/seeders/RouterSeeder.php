<?php

namespace Database\Seeders;

use App\Models\Router;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RouterSeeder extends Seeder
{
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Router::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        Router::create(
            [
                'code' => "default",
                'name' => "Router Default",
                'status' => "enabled",
            ]
        );
    }
}
