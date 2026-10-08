<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('user_tbls', function (Blueprint $table) {
            if (!Schema::hasColumn('user_tbls', 'contact')) {
                $table->string('contact')->nullable()->after('email');
            }
            if (!Schema::hasColumn('user_tbls', 'id_number')) {
                $table->string('id_number')->nullable()->after('contact');
            }
            if (!Schema::hasColumn('user_tbls', 'remarks')) {
                $table->text('remarks')->nullable()->after('status');
            }
        });
    }

    public function down(): void
    {
        Schema::table('user_tbls', function (Blueprint $table) {
            $columnsToDrop = array_filter(
                ['contact', 'id_number', 'remarks'],
                fn($column) => Schema::hasColumn('user_tbls', $column)
            );

            if (!empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
        });
    }
};