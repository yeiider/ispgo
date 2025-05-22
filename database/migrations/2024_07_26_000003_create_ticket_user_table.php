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
        Schema::create('ticket_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->timestamps();

            // Ensure a user can only be assigned to a ticket once
            $table->unique(['ticket_id', 'user_id']);
        });

        // Copy existing assignments from tickets table to the new pivot table
        DB::table('tickets')
            ->whereNotNull('user_id')
            ->select('id', 'user_id')
            ->get()
            ->each(function ($ticket) {
                DB::table('ticket_user')->insert([
                    'ticket_id' => $ticket->id,
                    'user_id' => $ticket->user_id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket_user');
    }
};
