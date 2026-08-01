<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'desa_id')) {
                $table->foreignId('desa_id')->nullable()->after('role')->constrained('desas')->nullOnDelete();
            }
        });

        Schema::table('panitias', function (Blueprint $table) {
            if (!Schema::hasColumn('panitias', 'desa_id')) {
                $table->foreignId('desa_id')->nullable()->after('id')->constrained('desas')->nullOnDelete();
            }
        });

        Schema::table('sponsors', function (Blueprint $table) {
            if (!Schema::hasColumn('sponsors', 'desa_id')) {
                $table->foreignId('desa_id')->nullable()->after('id')->constrained('desas')->nullOnDelete();
            }
        });

        Schema::table('dokumens', function (Blueprint $table) {
            if (!Schema::hasColumn('dokumens', 'desa_id')) {
                $table->foreignId('desa_id')->nullable()->after('id')->constrained('desas')->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('dokumens', function (Blueprint $table) {
            $table->dropForeign(['desa_id']);
            $table->dropColumn('desa_id');
        });
        Schema::table('sponsors', function (Blueprint $table) {
            $table->dropForeign(['desa_id']);
            $table->dropColumn('desa_id');
        });
        Schema::table('panitias', function (Blueprint $table) {
            $table->dropForeign(['desa_id']);
            $table->dropColumn('desa_id');
        });
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['desa_id']);
            $table->dropColumn('desa_id');
        });
    }
};
