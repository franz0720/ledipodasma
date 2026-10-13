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
        if (!Schema::hasColumn('course_tbls', 'start_time')) {
            Schema::table('course_tbls', function (Blueprint $table) {
                $table->time('start_time')->nullable();
            });
        }

        if (!Schema::hasColumn('course_tbls', 'end_time')) {
            Schema::table('course_tbls', function (Blueprint $table) {
                $table->time('end_time')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('course_tbls', 'start_time')) {
            Schema::table('course_tbls', function (Blueprint $table) {
                $table->dropColumn('start_time');
            });
        }

        if (Schema::hasColumn('course_tbls', 'end_time')) {
            Schema::table('course_tbls', function (Blueprint $table) {
                $table->dropColumn('end_time');
            });
        }
    }
};