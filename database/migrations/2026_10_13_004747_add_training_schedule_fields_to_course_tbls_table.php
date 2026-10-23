<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('course_tbls', 'start_date')) {
            Schema::table('course_tbls', function (Blueprint $table) {
                $table->date('start_date')->nullable();
            });
        }

        if (!Schema::hasColumn('course_tbls', 'end_date')) {
            Schema::table('course_tbls', function (Blueprint $table) {
                $table->date('end_date')->nullable();
            });
        }

        if (!Schema::hasColumn('course_tbls', 'training_days')) {
            Schema::table('course_tbls', function (Blueprint $table) {
                $table->json('training_days')->nullable();
            });
        }
    }

    public function down(): void
    {
        // Intentionally left empty to protect existing columns and data.
    }
};
