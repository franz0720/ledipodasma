<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('facility_resources', function (Blueprint $table) {
            $table->id();

            $table->foreignId('facility_id')
                ->constrained('facilities')
                ->cascadeOnDelete();

            $table->string('title', 150);
            $table->text('url');
            $table->text('description')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('facility_resources');
    }
};
