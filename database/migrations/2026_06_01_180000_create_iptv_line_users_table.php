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
        Schema::create('iptv_line_users', function (Blueprint $row) {
            $row->id();
            $row->foreignId('service_id')
                ->unique()
                ->constrained('services')
                ->onDelete('cascade');
            $row->unsignedBigInteger('line_id')->nullable();
            $row->string('username')->unique();
            $row->string('password');
            $row->integer('max_connections')->default(1);
            $row->timestamp('expire_date')->nullable();
            $row->text('bouquets')->nullable(); // Store comma-separated IDs or JSON array
            $row->string('status')->default('active'); // active, disabled, banned
            $row->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('iptv_line_users');
    }
};
