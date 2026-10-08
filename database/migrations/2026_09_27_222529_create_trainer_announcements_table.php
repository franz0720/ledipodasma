<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('trainer_announcements', function (Blueprint $table) {
            $table->id();
            // References course_tbls
            $table->foreignId('course_id')->constrained('course_tbls')->cascadeOnDelete();
            // References user_tbls
            $table->foreignId('trainer_id')->constrained('user_tbls')->cascadeOnDelete();
            $table->longText('content');
            $table->string('target_audience', 50)->default('all_students');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trainer_announcements');
    }
};