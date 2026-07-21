<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tabel kategori inventaris
        Schema::create('inventaris_kategori', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('keterangan')->nullable();
            $table->timestamps();
        });

        // Tabel barang inventaris
        Schema::create('inventaris', function (Blueprint $table) {
            $table->id();
            $table->string('kode')->unique()->nullable();
            $table->string('nama');
            $table->foreignId('kategori_id')->nullable()->constrained('inventaris_kategori')->nullOnDelete();
            $table->integer('jumlah_total')->default(0);
            $table->integer('jumlah_tersedia')->default(0);
            $table->enum('kondisi', ['baik', 'rusak_ringan', 'rusak_berat'])->default('baik');
            $table->string('lokasi')->nullable();
            $table->date('tanggal_pengadaan')->nullable();
            $table->decimal('harga_satuan', 15, 2)->nullable();
            $table->text('keterangan')->nullable();
            $table->string('foto')->nullable();
            $table->timestamps();
        });

        // Tabel peminjaman
        Schema::create('inventaris_peminjaman', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inventaris_id')->constrained('inventaris')->cascadeOnDelete();
            $table->string('peminjam');         // Nama peminjam
            $table->string('kontak')->nullable();
            $table->integer('jumlah')->default(1);
            $table->date('tanggal_pinjam');
            $table->date('tanggal_kembali_rencana');
            $table->date('tanggal_kembali_aktual')->nullable();
            $table->enum('status', ['dipinjam', 'dikembalikan', 'terlambat'])->default('dipinjam');
            $table->text('keterangan')->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventaris_peminjaman');
        Schema::dropIfExists('inventaris');
        Schema::dropIfExists('inventaris_kategori');
    }
};
