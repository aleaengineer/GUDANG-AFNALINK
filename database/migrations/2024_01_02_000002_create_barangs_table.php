<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('barangs', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('kategori');
            $table->string('merk')->nullable();
            $table->text('spesifikasi')->nullable();
            $table->string('serial_number')->nullable();
            $table->integer('stok')->default(0);
            $table->string('satuan')->default('pcs');
            $table->string('foto')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('barangs'); }
};
