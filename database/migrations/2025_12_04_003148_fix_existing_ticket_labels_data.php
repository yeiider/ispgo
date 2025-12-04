<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Fix any existing tickets with invalid labels data
        DB::table('tickets')->whereNotNull('labels')->each(function ($ticket) {
            $labels = $ticket->labels;

            // If labels is empty string or invalid JSON, set to null
            if (empty($labels) || $labels === '""' || $labels === "''") {
                DB::table('tickets')
                    ->where('id', $ticket->id)
                    ->update(['labels' => null]);
                return;
            }

            // Try to decode JSON
            $decoded = json_decode($labels, true);

            // If it's not valid JSON or not an array, set to null
            if (json_last_error() !== JSON_ERROR_NONE || !is_array($decoded)) {
                DB::table('tickets')
                    ->where('id', $ticket->id)
                    ->update(['labels' => null]);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No need to reverse this data fix
    }
};
