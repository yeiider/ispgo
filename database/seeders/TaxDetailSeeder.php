<?php

namespace Database\Seeders;

use App\Models\Customers\TaxDetail;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TaxDetailSeeder extends Seeder
{
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        TaxDetail::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        TaxDetail::factory()->count(50)->create();
    }
}
