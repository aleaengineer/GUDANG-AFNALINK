<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('barang_keluars', function (Blueprint $table) {
            $table->id();
            $table->foreignId('barang_id')->constrained('barangs')->onDelete('cascade');
            $table->integer('jumlah');
            $table->string('teknisi_nama');
            $table->enum('teknisi_jabatan', ['Teknisi','NOC','Marketing','Finance','CEO','CFO','CMO']);
            $table->string('serial_number')->nullable();
            $table->text('keperluan')->nullable();
            $table->foreignId('operator_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('barang_keluars'); }
};
