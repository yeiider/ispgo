<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    private const FIELDS = [
        'onepay_enabled',
        'onepay_base_url',
        'onepay_api_token',
        'onepay_auto_create_day',
        'onepay_auto_remind_day',
    ];

    /**
     * Move OnePay settings from their old top-level section (onepay/general/*)
     * into the payment gateways section (payment/onepay/*), matching the new
     * "métodos de pago" grouping in config/settings.php.
     */
    public function up(): void
    {
        foreach (self::FIELDS as $field) {
            $oldPath = 'onepay/general/' . $field;
            $newPath = 'payment/onepay/' . $field;

            $rows = DB::table('core_config_data')->where('path', $oldPath)->get();

            foreach ($rows as $row) {
                $exists = DB::table('core_config_data')
                    ->where('path', $newPath)
                    ->where('scope_id', $row->scope_id)
                    ->exists();

                if (!$exists) {
                    DB::table('core_config_data')->updateOrInsert(
                        ['scope_id' => $row->scope_id, 'path' => $newPath],
                        ['value' => $row->value, 'created_at' => now(), 'updated_at' => now()]
                    );
                }

                // Remove the old row so a single source of truth remains.
                DB::table('core_config_data')->where('id', $row->id)->delete();
            }
        }
    }

    /**
     * Reverse: move values back to onepay/general/*.
     */
    public function down(): void
    {
        foreach (self::FIELDS as $field) {
            $newPath = 'payment/onepay/' . $field;
            $oldPath = 'onepay/general/' . $field;

            $rows = DB::table('core_config_data')->where('path', $newPath)->get();

            foreach ($rows as $row) {
                $exists = DB::table('core_config_data')
                    ->where('path', $oldPath)
                    ->where('scope_id', $row->scope_id)
                    ->exists();

                if (!$exists) {
                    DB::table('core_config_data')->updateOrInsert(
                        ['scope_id' => $row->scope_id, 'path' => $oldPath],
                        ['value' => $row->value, 'created_at' => now(), 'updated_at' => now()]
                    );
                }

                DB::table('core_config_data')->where('id', $row->id)->delete();
            }
        }
    }
};
