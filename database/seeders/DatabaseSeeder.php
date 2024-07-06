<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(RolesAndPermissionsSeeder::class);
        $this->call(RouterSeeder::class);
        $this->call(CustomerSeeder::class);
        $this->call(AddressSeeder::class);
        $this->call(TaxDetailSeeder::class);
        $this->call(PlanSeeder::class);
        $this->call(ServiceSeeder::class);

    }
}
