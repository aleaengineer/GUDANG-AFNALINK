<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('barangs', function (Blueprint $table) {
            $table->enum('deletion_status', ['none','pending','approved','rejected'])->default('none')->after('foto');
            $table->foreignId('deletion_requested_by')->nullable()->after('deletion_status')->constrained('users')->nullOnDelete();
            $table->timestamp('deletion_requested_at')->nullable()->after('deletion_requested_by');
            $table->foreignId('deletion_reviewed_by')->nullable()->after('deletion_requested_at')->constrained('users')->nullOnDelete();
            $table->timestamp('deletion_reviewed_at')->nullable()->after('deletion_reviewed_by');
        });
        // Kembalikan barang_masuk ke tanpa verifikasi (hapus kolom verifikasi jika ada, tapi kita biarkan saja, hanya tidak dipakai)
        // Untuk clean, kita tidak hapus kolom barang_masuk, tapi verifikasi akan pindah ke barangs
        // Hapus pending lama di barang_masuk untuk clean
        \Illuminate\Support\Facades\DB::table('barang_masuks')->where('deletion_status','pending')->update(['deletion_status'=>'none','deletion_requested_by'=>null,'deletion_requested_at'=>null]);
    }

    public function down(): void
    {
        Schema::table('barangs', function (Blueprint $table) {
            $table->dropColumn(['deletion_status','deletion_requested_by','deletion_requested_at','deletion_reviewed_by','deletion_reviewed_at']);
        });
    }
};
