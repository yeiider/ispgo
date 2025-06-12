<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        Schema::create('nap_distributions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('nap_box_id')->constrained('nap_boxes')->onDelete('cascade');
            $table->float('flow_position_x');
            $table->float('flow_position_y');
            $table->float('flow_level');
            $table->json('connection_data')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nap_distributions');
    }
};
