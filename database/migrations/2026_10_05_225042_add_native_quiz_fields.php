<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add instructions to the quizzes table
        Schema::table('quizzes', function (Blueprint $table) {
            $table->text('instructions')
                ->nullable()
                ->after('title');
        });

        // Add fields for the new quiz maker
        Schema::table('quiz_questions', function (Blueprint $table) {
            $table->string('question_type')
                ->default('multiple_choice')
                ->after('question');

            $table->json('options')
                ->nullable()
                ->after('question_type');

            $table->json('correct_answers')
                ->nullable()
                ->after('correct_answer');

            $table->integer('points')
                ->default(1)
                ->after('correct_answers');

            $table->text('feedback')
                ->nullable()
                ->after('points');

            $table->boolean('required')
                ->default(false)
                ->after('feedback');
        });
    }

    public function down(): void
    {
        Schema::table('quiz_questions', function (Blueprint $table) {
            $table->dropColumn([
                'question_type',
                'options',
                'correct_answers',
                'points',
                'feedback',
                'required',
            ]);
        });

        Schema::table('quizzes', function (Blueprint $table) {
            $table->dropColumn('instructions');
        });
    }
};