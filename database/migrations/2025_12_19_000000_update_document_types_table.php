<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class UpdateDocumentTypesTable extends Migration
{
    public function up()
    {
        // Eliminar Tarjeta de Identidad (TI)
        DB::table('document_types')->where('code', 'TI')->delete();

        // Agregar los nuevos tipos de documento si no existen
        $newTypes = [
            ['code' => 'CE', 'name' => 'Cédula de Extranjería'],
            ['code' => 'DNI', 'name' => 'Documento Nacional de Identidad'],
        ];

        foreach ($newTypes as $type) {
            $exists = DB::table('document_types')->where('code', $type['code'])->exists();
            if (!$exists) {
                DB::table('document_types')->insert([
                    'code' => $type['code'],
                    'name' => $type['name'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // Actualizar el nombre de NIT si es necesario
        DB::table('document_types')
            ->where('code', 'NIT')
            ->update(['name' => 'Número de Identificación Tributaria']);
    }

    public function down()
    {
        // Restaurar Tarjeta de Identidad
        $exists = DB::table('document_types')->where('code', 'TI')->exists();
        if (!$exists) {
            DB::table('document_types')->insert([
                'code' => 'TI',
                'name' => 'Tarjeta de Identidad',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Eliminar los nuevos tipos
        DB::table('document_types')->whereIn('code', ['CE', 'DNI'])->delete();

        // Restaurar nombre original de NIT
        DB::table('document_types')
            ->where('code', 'NIT')
            ->update(['name' => 'Registro Nacional de Tributos']);
    }
}

