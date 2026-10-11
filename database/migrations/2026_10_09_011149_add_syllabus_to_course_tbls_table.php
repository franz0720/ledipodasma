<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('course_tbls', function (Blueprint $table) {
            $table->string('syllabus_title')->nullable()->after('description');
            $table->text('syllabus_content')->nullable()->after('syllabus_title');
            $table->string('syllabus_path')->nullable()->after('syllabus_content');
        });
    }

    public function down(): void
    {
        Schema::table('course_tbls', function (Blueprint $table) {
            $table->dropColumn(['syllabus_title', 'syllabus_content', 'syllabus_path']);
        });
    }
};