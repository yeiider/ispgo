<?php

namespace Database\Seeders;

use App\Models\Customers\Customer;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CustomerSeeder extends Seeder
{
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Customer::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        Customer::factory()->count(50)->create();
    }
}
