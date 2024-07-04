<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateTaxpayerTypesTable extends Migration
{
    public function up()
    {
        Schema::create('taxpayer_types', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->timestamps();
        });

        DB::table('taxpayer_types')->insert([
            ['code' => 'individual', 'name' => 'Individual'],
            ['code' => 'corporation', 'name' => 'Corporation'],
            ['code' => 'sole_proprietorship', 'name' => 'Sole Proprietorship'],
            ['code' => 'partnership', 'name' => 'Partnership'],
            ['code' => 'trust', 'name' => 'Trust'],
            ['code' => 'estate', 'name' => 'Estate'],
            ['code' => 'non_profit', 'name' => 'Non-Profit Organization'],
            ['code' => 'government', 'name' => 'Government Entity'],
            ['code' => 'foreign', 'name' => 'Foreign Entity'],
            ['code' => 'freelancer', 'name' => 'Freelancer'],
            ['code' => 'self_employed', 'name' => 'Self-Employed'],
            ['code' => 'small_business', 'name' => 'Small Business'],
            ['code' => 'large_business', 'name' => 'Large Business']
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('taxpayer_types');
    }
}
