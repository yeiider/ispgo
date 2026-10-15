<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Backfill de cash_transfers: sincroniza las columnas polimórficas
 * (sender_type/sender_id, receiver_type/receiver_id) con las legacy
 * (sender_cash_register_id / receiver_cash_register_id).
 *
 * Las transferencias creadas tras la migración de polimorfismo quedaron sin
 * las columnas legacy, por lo que no aparecían en reportes ni cierres de caja.
 * Solo rellena valores NULL: nunca sobreescribe datos existentes.
 */
return new class extends Migration
{
    private const CASH_REGISTER_MORPH = 'App\\Models\\Finance\\CashRegister';

    public function up(): void
    {
        foreach (['sender', 'receiver'] as $side) {
            $typeCol   = "{$side}_type";
            $idCol     = "{$side}_id";
            $legacyCol = "{$side}_cash_register_id";

            // Polimórfico -> legacy
            DB::table('cash_transfers')
                ->where($typeCol, self::CASH_REGISTER_MORPH)
                ->whereNotNull($idCol)
                ->whereNull($legacyCol)
                ->orderBy('id')
                ->select(['id', $idCol])
                ->chunkById(200, function ($rows) use ($idCol, $legacyCol) {
                    foreach ($rows as $row) {
                        DB::table('cash_transfers')
                            ->where('id', $row->id)
                            ->update([$legacyCol => $row->{$idCol}]);
                    }
                });

            // Legacy -> polimórfico (registros anteriores al polimorfismo)
            DB::table('cash_transfers')
                ->whereNotNull($legacyCol)
                ->whereNull($typeCol)
                ->orderBy('id')
                ->select(['id', $legacyCol])
                ->chunkById(200, function ($rows) use ($typeCol, $idCol, $legacyCol) {
                    foreach ($rows as $row) {
                        DB::table('cash_transfers')
                            ->where('id', $row->id)
                            ->update([
                                $typeCol => self::CASH_REGISTER_MORPH,
                                $idCol   => $row->{$legacyCol},
                            ]);
                    }
                });
        }
    }

    public function down(): void
    {
        // Backfill de datos: no reversible de forma segura (no se borran datos válidos).
    }
};
