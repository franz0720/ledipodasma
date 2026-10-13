
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quiz_attempt_histories', function (Blueprint $table) {
            $table->id();

            $table->foreignId('quiz_id')
                ->constrained('quizzes')
                ->cascadeOnDelete();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->unsignedInteger('attempt_number');
            $table->unsignedInteger('score')->default(0);
            $table->unsignedInteger('total_items')->default(0);
            $table->decimal('percentage', 5, 2)->default(0);
            $table->string('status', 30)->default('failed');
            $table->boolean('timed_out')->default(false);

            $table->timestamps();

            $table->unique(
                ['quiz_id', 'user_id', 'attempt_number'],
                'quiz_attempt_histories_attempt_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quiz_attempt_histories');
    }
};
