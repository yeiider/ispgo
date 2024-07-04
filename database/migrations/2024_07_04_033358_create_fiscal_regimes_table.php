<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateFiscalRegimesTable extends Migration
{
    public function up()
    {
        Schema::create('fiscal_regimes', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->timestamps();
        });

        DB::table('fiscal_regimes')->insert([
            ['code' => 'general', 'name' => 'General Regime'],
            ['code' => 'simplified', 'name' => 'Simplified Regime'],
            ['code' => 'small_contributor', 'name' => 'Small Contributor Regime'],
            ['code' => 'large_contributor', 'name' => 'Large Contributor Regime'],
            ['code' => 'self_employed', 'name' => 'Self-Employed Regime'],
            ['code' => 'corporate', 'name' => 'Corporate Regime'],
            ['code' => 'non_profit', 'name' => 'Non-Profit Regime'],
            ['code' => 'trust', 'name' => 'Trust Regime'],
            ['code' => 'partnership', 'name' => 'Partnership Regime'],
            ['code' => 'foreign', 'name' => 'Foreign Regime'],
            ['code' => 'individual', 'name' => 'Individual Regime'],
            ['code' => 'freelancer', 'name' => 'Freelancer Regime'],
            ['code' => 'agricultural', 'name' => 'Agricultural Regime'],
            ['code' => 'micro_enterprise', 'name' => 'Micro Enterprise Regime'],
            ['code' => 'special', 'name' => 'Special Regime'],
            ['code' => 'transitional', 'name' => 'Transitional Regime'],
            ['code' => 'private', 'name' => 'Private Regime']
        ]);

    }

    public function down()
    {
        Schema::dropIfExists('fiscal_regimes');
    }
}
