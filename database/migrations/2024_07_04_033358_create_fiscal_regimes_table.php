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
            ['code' => 'general', 'name' => 'Regimen común'],
            ['code' => 'simplified', 'name' => 'Regimen simplificado'],
        ]);

    }

    public function down()
    {
        Schema::dropIfExists('fiscal_regimes');
    }
}
