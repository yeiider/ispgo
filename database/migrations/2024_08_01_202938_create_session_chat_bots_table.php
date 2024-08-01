<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('session_chat_bots', function (Blueprint $table) {
            $table->id();
            $table->string('chat_id');
            $table->string('user_id');
            $table->string('current_option')->nullable();
            $table->text('message_history')->nullable();
            $table->json('interaction_history')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('session_chat_bots');
    }
};
