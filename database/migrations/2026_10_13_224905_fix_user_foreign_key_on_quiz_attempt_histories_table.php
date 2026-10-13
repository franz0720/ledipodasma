
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('quiz_attempt_histories', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });

        Schema::table('quiz_attempt_histories', function (Blueprint $table) {
            $table->foreign('user_id')
                ->references('id')
                ->on('user_tbls')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('quiz_attempt_histories', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });

        Schema::table('quiz_attempt_histories', function (Blueprint $table) {
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->cascadeOnDelete();
        });
    }
};
