
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('admin_notifications')) {
            Schema::create('admin_notifications', function (Blueprint $table) {
                $table->id();

                $table->unsignedBigInteger('user_id');
                $table->string('event_key');

                $table->string('type')->default('info');
                $table->string('title');
                $table->text('message');
                $table->string('url')->nullable();
                $table->string('icon_class')->nullable();

                $table->timestamp('read_at')->nullable();
                $table->timestamps();

                // Prevent duplicate events for the same administrator.
                $table->unique(
                    ['user_id', 'event_key'],
                    'admin_notifications_user_event_unique'
                );

                $table->foreign('user_id')
                    ->references('id')
                    ->on('user_tbls')
                    ->onDelete('cascade');

                $table->index(['user_id', 'read_at']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('admin_notifications');
    }
};
