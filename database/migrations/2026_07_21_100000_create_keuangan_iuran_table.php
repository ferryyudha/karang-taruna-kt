<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('keuangan_iuran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('anggota_id')->constrained('anggota')->onDelete('cascade');
            $table->unsignedTinyInteger('bulan'); // 1..12
            $table->unsignedSmallInteger('tahun'); // e.g. 2026
            $table->decimal('nominal', 15, 2)->default(10000.00);
            $table->enum('status', ['belum_bayar', 'lunas', 'dibatalkan'])->default('belum_bayar');
            $table->date('tanggal_bayar')->nullable();
            $table->foreignId('kas_id')->nullable()->constrained('keuangan_kas')->onDelete('set null');
            $table->foreignId('kategori_id')->nullable()->constrained('keuangan_kategori')->onDelete('set null');
            $table->foreignId('transaksi_id')->nullable()->constrained('keuangan_transaksi')->onDelete('set null');
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->unique(['anggota_id', 'bulan', 'tahun']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('keuangan_iuran');
    }
};
