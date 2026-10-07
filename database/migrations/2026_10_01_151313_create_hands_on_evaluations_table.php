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
    Schema::create('hands_on_evaluations', function (Blueprint $table) {
        $table->id();

        $table->foreignId('user_id')
            ->constrained('user_tbls')
            ->cascadeOnDelete();

        $table->foreignId('course_id')
            ->constrained('course_tbls')
            ->cascadeOnDelete();

        $table->foreignId('trainer_id')
            ->constrained('user_tbls')
            ->cascadeOnDelete();

        $table->enum('result', ['passed', 'failed']);

        $table->dateTime('evaluated_at');

        $table->text('remarks')->nullable();

        $table->timestamps();

        $table->unique(['user_id', 'course_id']);
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hands_on_evaluations');
    }
};
