<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('keuangan_kas', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->text('keterangan')->nullable();
            $table->decimal('saldo', 15, 2)->default(0.00);
            $table->timestamps();
        });

        Schema::create('keuangan_kategori', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->enum('tipe', ['pemasukan', 'pengeluaran']);
            $table->timestamps();
        });

        Schema::create('keuangan_transaksi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kas_id')->constrained('keuangan_kas')->onDelete('cascade');
            $table->foreignId('kategori_id')->constrained('keuangan_kategori')->onDelete('cascade');
            $table->enum('tipe', ['pemasukan', 'pengeluaran']);
            $table->decimal('jumlah', 15, 2);
            $table->date('tanggal');
            $table->text('keterangan')->nullable();
            $table->string('bukti_foto')->nullable();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('keuangan_transaksi');
        Schema::dropIfExists('keuangan_kategori');
        Schema::dropIfExists('keuangan_kas');
    }
};
