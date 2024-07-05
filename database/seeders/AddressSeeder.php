<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Address;
use Illuminate\Support\Facades\DB;

class AddressSeeder extends Seeder
{
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Address::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        Address::factory()->count(50)->create();
    }
}
