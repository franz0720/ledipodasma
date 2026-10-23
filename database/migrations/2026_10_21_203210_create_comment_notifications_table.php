<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('comment_notifications', function (Blueprint $table) {
            $table->id();

            // Student who will receive the notification
            $table->unsignedBigInteger('user_id')->index();

            // Optional reference to the related announcement
            $table->unsignedBigInteger('announcement_id')->nullable()->index();

            // Optional reference to the comment
            $table->unsignedBigInteger('comment_id')->nullable()->index();

            $table->string('type')->default('comment_reply');
            $table->string('message');
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('comment_notifications');
    }
};
