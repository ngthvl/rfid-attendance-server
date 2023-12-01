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
        Schema::create('rfid_tag_allocations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('tag_data')->unique()->index();
            $table->string('allocation_id')->index();
            $table->string('allocation_type')->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rfid_tag_allocations');
    }
};
