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
        Schema::table('students', function (Blueprint $table) {
            $table->string('avatar')->nullable();
            $table->bigInteger('section_id')->nullable();
            $table->bigInteger('education_level_id')->nullable();
            $table->string('school_year')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn('avatar');
            $table->dropColumn('section_id');
            $table->dropColumn('education_level_id');
            $table->dropColumn('school_year');
        });
    }
};
