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
        Schema::table('rfid_outputs', function (Blueprint $table) {
            $table->date('date_detected')->nullable();
        });

        DB::statement('UPDATE rfid_outputs SET date_detected=detection_dt WHERE true ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rfid_outputs', function (Blueprint $table) {
            $table->dropColumn('date_detected');
        });
    }
};
