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
Schema::table('users', function (Blueprint $table) {
            // idempotent-ish: kalau kolom sudah ada, migration ini tidak boleh gagal
            if (!Schema::hasColumn('users', 'no_hp')) {
                $table->string('no_hp')->unique()->after('name');
            }

            if (!Schema::hasColumn('users', 'role')) {
                $table->string('role')->default('customer')->after('no_hp');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');
            $table->dropColumn('no_hp');
        });
    }
};



