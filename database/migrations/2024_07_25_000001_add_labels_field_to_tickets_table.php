<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add the labels field to the tickets table
        Schema::table('tickets', function (Blueprint $table) {
            $table->json('labels')->nullable()->after('contact_method');
        });

        // Migrate existing labels to the new field
        $tickets = DB::table('tickets')->get();
        foreach ($tickets as $ticket) {
            $labels = DB::table('ticket_label')
                ->join('ticket_labels', 'ticket_label.ticket_label_id', '=', 'ticket_labels.id')
                ->where('ticket_label.ticket_id', $ticket->id)
                ->select('ticket_labels.name', 'ticket_labels.color')
                ->get()
                ->toArray();

            if (!empty($labels)) {
                DB::table('tickets')
                    ->where('id', $ticket->id)
                    ->update(['labels' => json_encode($labels)]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropColumn('labels');
        });
    }
};
