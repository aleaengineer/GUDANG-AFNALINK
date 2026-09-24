<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('barang_masuks', function (Blueprint $table) {
            $table->enum('deletion_status', ['none','pending','approved','rejected'])->default('none')->after('operator_id');
            $table->foreignId('deletion_requested_by')->nullable()->after('deletion_status')->constrained('users')->nullOnDelete();
            $table->timestamp('deletion_requested_at')->nullable()->after('deletion_requested_by');
            $table->foreignId('deletion_reviewed_by')->nullable()->after('deletion_requested_at')->constrained('users')->nullOnDelete();
            $table->timestamp('deletion_reviewed_at')->nullable()->after('deletion_reviewed_by');
            $table->text('deletion_reason')->nullable()->after('deletion_reviewed_at');
        });
    }

    public function down(): void
    {
        Schema::table('barang_masuks', function (Blueprint $table) {
            $table->dropColumn(['deletion_status','deletion_requested_by','deletion_requested_at','deletion_reviewed_by','deletion_reviewed_at','deletion_reason']);
        });
    }
};
