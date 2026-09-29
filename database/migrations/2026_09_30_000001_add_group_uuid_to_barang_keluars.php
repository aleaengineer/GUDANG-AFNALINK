<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Satu pencatatan bisa berisi beberapa barang -> semua baris di satu
        // pencatatan berbagi group_uuid yang sama.
        DB::statement("ALTER TABLE barang_keluars ADD COLUMN group_uuid CHAR(36) NULL AFTER taken_by");
        DB::statement("ALTER TABLE barang_keluars ADD INDEX barang_keluars_group_uuid_index (group_uuid)");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE barang_keluars DROP INDEX barang_keluars_group_uuid_index");
        DB::statement("ALTER TABLE barang_keluars DROP COLUMN group_uuid");
    }
};
