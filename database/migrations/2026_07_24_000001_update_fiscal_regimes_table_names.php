<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class UpdateFiscalRegimesTableNames extends Migration
{
    public function up()
    {
        DB::table('fiscal_regimes')
            ->where('code', 'general')
            ->update(['name' => 'Responsable de IVA']);

        DB::table('fiscal_regimes')
            ->where('code', 'simplified')
            ->update(['name' => 'No responsable de IVA']);
    }

    public function down()
    {
        DB::table('fiscal_regimes')
            ->where('code', 'general')
            ->update(['name' => 'Regimen común']);

        DB::table('fiscal_regimes')
            ->where('code', 'simplified')
            ->update(['name' => 'Regimen simplificado']);
    }
}
