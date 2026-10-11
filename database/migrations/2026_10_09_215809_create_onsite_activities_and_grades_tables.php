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
        // 1. Table for practical exams / onsite workshop tasks
        Schema::create('onsite_activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained('course_tbls')->cascadeOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->decimal('max_score', 8, 2)->default(100.00);
            $table->date('activity_date');
            $table->timestamps();
        });

        // 2. Table for student scores per activity
        Schema::create('onsite_grades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('onsite_activity_id')->constrained('onsite_activities')->cascadeOnDelete();
            $table->foreignId('enrollment_id')->constrained('enrollment_tbls')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('user_tbls')->cascadeOnDelete();
            $table->decimal('score', 8, 2)->nullable();
            $table->text('remarks')->nullable();
            $table->foreignId('graded_by')->nullable()->constrained('user_tbls')->nullOnDelete();
            $table->timestamps();

            // Prevent duplicate score entries for the same enrollment & activity
            $table->unique(['onsite_activity_id', 'enrollment_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('onsite_grades');
        Schema::dropIfExists('onsite_activities');
    }
};