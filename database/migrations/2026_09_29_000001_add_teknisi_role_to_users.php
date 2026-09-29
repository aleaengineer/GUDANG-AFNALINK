<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin','operator','teknisi') NOT NULL DEFAULT 'operator'");
        DB::statement("ALTER TABLE barang_keluars ADD COLUMN taken_by BIGINT UNSIGNED NULL AFTER operator_id");
        DB::statement("ALTER TABLE barang_keluars ADD CONSTRAINT barang_keluars_taken_by_foreign FOREIGN KEY (taken_by) REFERENCES users(id) ON DELETE SET NULL");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE barang_keluars DROP FOREIGN KEY barang_keluars_taken_by_foreign");
        DB::statement("ALTER TABLE barang_keluars DROP COLUMN taken_by");
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin','operator') NOT NULL DEFAULT 'operator'");
    }
};
