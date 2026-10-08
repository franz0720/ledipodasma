<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('announcement_comments', function (Blueprint $table) {
            $table->id();

            // Foreign key to trainer_announcements table
            $table->foreignId('announcement_id')
                  ->constrained('trainer_announcements')
                  ->onDelete('cascade');

            // Foreign key to user_tbls table
            $table->foreignId('user_id')
                  ->constrained('user_tbls')
                  ->onDelete('cascade');

            $table->text('comment');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('announcement_comments');
    }
};