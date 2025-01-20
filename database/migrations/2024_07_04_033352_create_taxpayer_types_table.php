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
            ['code' => 'personas_naturales', 'name' => 'Personas Naturales'],
            ['code' => 'personas_juridicas', 'name' => 'Personas Jurídicas'],
            ['code' => 'regimen_simple', 'name' => 'Régimen Simple de Tributación'],
            ['code' => 'regimen_ordinario', 'name' => 'Régimen Ordinario'],
            ['code' => 'entidades_sin_animo_de_lucro', 'name' => 'Entidades Sin Ánimo de Lucro'],
            ['code' => 'entidades_gubernamentales', 'name' => 'Entidades Gubernamentales'],
            ['code' => 'grandes_contribuyentes', 'name' => 'Grandes Contribuyentes'],
            ['code' => 'micro_empresas', 'name' => 'Microempresas'],
            ['code' => 'pequenas_y_medianas_empresas', 'name' => 'Pequeñas y Medianas Empresas (PYMES)'],
            ['code' => 'consorcios_y_uniones_temporales', 'name' => 'Consorcios y Uniones Temporales'],
            ['code' => 'profesionales_independientes', 'name' => 'Profesionales Independientes'],
            ['code' => 'regimen_especial', 'name' => 'Régimen Especial']
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('taxpayer_types');
    }
}
