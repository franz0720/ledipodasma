<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trainer_announcement_reads', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('trainer_announcement_id');
            $table->timestamp('read_at')->nullable();
            $table->timestamps();

            $table->unique(
                ['user_id', 'trainer_announcement_id'],
                'trainer_announcement_reads_user_announcement_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trainer_announcement_reads');
    }
};
