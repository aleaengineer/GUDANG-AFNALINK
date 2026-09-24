<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Update users.jabatan to include Kasir
        DB::statement("ALTER TABLE users MODIFY COLUMN jabatan ENUM('Teknisi','NOC','Marketing','Kasir','CEO','CFO','CMO','Finance') NOT NULL DEFAULT 'Teknisi'");
        // Update barang_keluars.teknisi_jabatan to include Kasir
        DB::statement("ALTER TABLE barang_keluars MODIFY COLUMN teknisi_jabatan ENUM('Teknisi','NOC','Marketing','Kasir','CEO','CFO','CMO','Finance') NOT NULL");
        // Update karyawans.jabatan already has Kasir, ensure it also has Finance for backward compat (already does)
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE users MODIFY COLUMN jabatan ENUM('Teknisi','NOC','Marketing','Finance','CEO','CFO','CMO') NOT NULL DEFAULT 'Teknisi'");
        DB::statement("ALTER TABLE barang_keluars MODIFY COLUMN teknisi_jabatan ENUM('Teknisi','NOC','Marketing','Finance','CEO','CFO','CMO') NOT NULL");
    }
};
