<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateDocumentTypesTable extends Migration
{
    public function up()
    {
        Schema::create('document_types', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->timestamps();
        });

        // Insert initial document types
        DB::table('document_types')->insert([
            ['code' => 'CC', 'name' => 'Cédula de Ciudadanía'],
            ['code' => 'TI', 'name' => 'Tarjeta de Identidad'],
            ['code' => 'NIT', 'name' => 'Registro Nacional de Tributos']
//            ['code' => 'INE', 'name' => 'Instituto Nacional Electoral'],
//            ['code' => 'CURP', 'name' => 'Clave Única de Registro de Población'],
//            ['code' => 'NIE', 'name' => 'Número de Identidad de Extranjero'],
//            ['code' => 'CPF', 'name' => 'Cadastro de Pessoas Físicas'],
//            ['code' => 'RG', 'name' => 'Registro Geral'],
//            ['code' => 'SSN', 'name' => 'Social Security Number'],
//            ['code' => 'Passport', 'name' => 'Pasaporte'],
//            ['code' => 'Drivers_License', 'name' => 'Licencia de Conducir'],
//            ['code' => 'NID', 'name' => 'National Identity Document'],
//            ['code' => 'Aadhaar', 'name' => 'Aadhaar'],
//            ['code' => 'NIN', 'name' => 'National Insurance Number'],
//            ['code' => 'CIP', 'name' => 'Certificado de Identificación Personal'],
//            ['code' => 'KTP', 'name' => 'Kartu Tanda Penduduk'],
//            ['code' => 'CNIC', 'name' => 'Computerized National Identity Card'],
//            ['code' => 'IC', 'name' => 'Identity Card'],
//            ['code' => 'HKID', 'name' => 'Hong Kong Identity Card'],
//            ['code' => 'NRIC', 'name' => 'National Registration Identity Card'],
//            ['code' => 'MyKad', 'name' => 'MyKad'],
//            ['code' => 'BVN', 'name' => 'Bank Verification Number'],
//            ['code' => 'OIB', 'name' => 'Osobni Identifikacijski Broj'],
//            ['code' => 'SID', 'name' => 'Sistema de Identificación de Datos'],
//            ['code' => 'JMBG', 'name' => 'Jedinstveni matični broj građana'],
//            ['code' => 'EMID', 'name' => 'Emirates ID']
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('document_types');
    }
}
