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
    Schema::create('trainer_notifications', function (Blueprint $table) {
        $table->id();

        // Trainer receiving this notification
        $table->unsignedBigInteger('user_id')->index();

        // Related course, when applicable
        $table->unsignedBigInteger('course_id')->nullable()->index();

        // enrollment, comment, schedule, assessment, or system
        $table->string('type', 50)->index();

        $table->string('title', 150);
        $table->text('message');

        // Optional destination within the application
        $table->string('url', 500)->nullable();

        // NULL means unread
        $table->timestamp('read_at')->nullable()->index();

        $table->timestamps();
    });
}

public function down(): void
{
    Schema::dropIfExists('trainer_notifications');
}
};
