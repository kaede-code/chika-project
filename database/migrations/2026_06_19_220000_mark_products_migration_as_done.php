<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Tidak ada perubahan skema. Hanya helper agar migrasi produk yang sudah terlanjur ada di DB
        // tidak memblokir "migrate" berikutnya.
        //
        // NOTE: Jika proyek kamu dijalankan dari migrasi standar, file ini bisa dihapus.
        $batch = (int) (DB::table('migrations')->max('batch') ?? 0) + 1;

        $migrationName = '2026_06_19_210618_create_products_table';

        $exists = DB::table('migrations')
            ->where('migration', $migrationName)
            ->exists();

        if (!$exists) {
            DB::table('migrations')->insert([
                'migration' => $migrationName,
                'batch' => $batch,
            ]);
        }
    }

    public function down(): void
    {
        DB::table('migrations')->where('migration', '2026_06_19_210618_create_products_table')->delete();
    }
};

