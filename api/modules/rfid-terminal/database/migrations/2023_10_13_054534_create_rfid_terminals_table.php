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
        Schema::create('rfid_terminals', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('device_name')->nullable();
            $table->string('ip_address')->nullable();
            $table->boolean('authenticated')->default(false);
            $table->json('devices_status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rfid_terminals');
    }
};
