<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateTaxIdentificationTypesTable extends Migration
{
    public function up()
    {
        Schema::create('tax_identification_types', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->timestamps();
        });

        DB::table('tax_identification_types')->insert([
            ['code' => 'NIT', 'name' => 'Número de Identificación Tributaria'],
            ['code' => 'RUT', 'name' => 'Registro Único Tributario'],
            ['code' => 'RFC', 'name' => 'Registro Federal de Contribuyentes'],
            ['code' => 'VAT', 'name' => 'Value Added Tax Number'],
            ['code' => 'GST', 'name' => 'Goods and Services Tax Number'],
            ['code' => 'CIF', 'name' => 'Código de Identificación Fiscal'],
            ['code' => 'TIN', 'name' => 'Tax Identification Number'],
            ['code' => 'EIN', 'name' => 'Employer Identification Number'],
            ['code' => 'PAN', 'name' => 'Permanent Account Number'],
            ['code' => 'ABN', 'name' => 'Australian Business Number'],
            ['code' => 'TRN', 'name' => 'Tax Registration Number'],
            ['code' => 'CRN', 'name' => 'Commercial Registration Number'],
            ['code' => 'BRN', 'name' => 'Business Registration Number'],
            ['code' => 'BN', 'name' => 'Business Number'],
            ['code' => 'UTR', 'name' => 'Unique Taxpayer Reference'],
            ['code' => 'BP', 'name' => 'Business Partner Number'],
            ['code' => 'IRD', 'name' => 'Inland Revenue Department Number'],
            ['code' => 'ARS', 'name' => 'Australian Registered Scheme Number'],
        ]);

    }

    public function down()
    {
        Schema::dropIfExists('tax_identification_types');
    }
}
