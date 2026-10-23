
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('quizzes', function (Blueprint $table) {
            $table->unsignedTinyInteger('attempts_allowed')
                ->default(2);
        });

        Schema::table('quiz_results', function (Blueprint $table) {
            $table->unsignedSmallInteger('attempt_number')
                ->default(1);

            $table->index(
                ['quiz_id', 'user_id', 'attempt_number'],
                'quiz_results_attempt_lookup_idx'
            );
        });

        // Assign attempt numbers to historical results.
        DB::statement("
            UPDATE quiz_results AS qr
            JOIN (
                SELECT id, attempt_number
                FROM (
                    SELECT
                        id,
                        ROW_NUMBER() OVER (
                            PARTITION BY quiz_id, user_id
                            ORDER BY created_at ASC, id ASC
                        ) AS attempt_number
                    FROM quiz_results
                ) AS ranked
            ) AS numbered ON numbered.id = qr.id
            SET qr.attempt_number = numbered.attempt_number
        ");
    }

    public function down(): void
    {
        Schema::table('quiz_results', function (Blueprint $table) {
            $table->dropIndex(
                'quiz_results_attempt_lookup_idx'
            );

            $table->dropColumn('attempt_number');
        });

        Schema::table('quizzes', function (Blueprint $table) {
            $table->dropColumn('attempts_allowed');
        });
    }
};
