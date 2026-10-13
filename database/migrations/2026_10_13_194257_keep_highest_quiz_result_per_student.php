<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('quiz_results', function (Blueprint $table) {
            $table->unsignedSmallInteger('attempts_used')
                ->default(1)
                ->after('status');
        });

        /*
         * Keep only the best result for each student and quiz.
         * When scores tie, keep the most recently saved record.
         * Count any existing result rows as previous attempts.
         */
        $groups = DB::table('quiz_results')
            ->select('quiz_id', 'user_id')
            ->groupBy('quiz_id', 'user_id')
            ->get();

        foreach ($groups as $group) {
            $rows = DB::table('quiz_results')
                ->where('quiz_id', $group->quiz_id)
                ->where('user_id', $group->user_id)
                ->orderByDesc('percentage')
                ->orderByDesc('score')
                ->orderByDesc('id')
                ->get();

            if ($rows->isEmpty()) {
                continue;
            }

            $best = $rows->first();

            $attemptsUsed = max(
                $rows->count(),
                (int) $rows->max('attempts_used')
            );

            DB::table('quiz_results')
                ->where('id', $best->id)
                ->update([
                    'attempts_used' => $attemptsUsed,
                ]);

            // Remove lower-scoring duplicate result rows.
            DB::table('quiz_results')
                ->where('quiz_id', $group->quiz_id)
                ->where('user_id', $group->user_id)
                ->where('id', '<>', $best->id)
                ->delete();
        }

        // Enforce one result row per student per quiz.
        Schema::table('quiz_results', function (Blueprint $table) {
            $table->unique(
                ['quiz_id', 'user_id'],
                'quiz_results_quiz_user_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::table('quiz_results', function (Blueprint $table) {
            $table->dropUnique('quiz_results_quiz_user_unique');
            $table->dropColumn('attempts_used');
        });
    }
};
