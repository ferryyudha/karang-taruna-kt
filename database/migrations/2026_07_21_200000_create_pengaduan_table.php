<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengaduan', function (Blueprint $table) {
            $table->id();
            $table->string('kode_tiket')->unique(); // e.g. LAP-202607-A9K2
            $table->string('nama_pelapor');
            $table->string('phone_pelapor');
            $table->string('email_pelapor')->nullable();
            $table->enum('kategori', ['jalan_rusak', 'sampah', 'drainase', 'lampu_jalan', 'keamanan', 'lainnya'])->default('lainnya');
            $table->string('lokasi');
            $table->string('judul');
            $table->text('isi_laporan');
            $table->string('foto_bukti')->nullable();
            $table->enum('status', ['diterima', 'diproses', 'selesai', 'ditolak'])->default('diterima');
            $table->text('tanggapan')->nullable();
            $table->string('foto_penanganan')->nullable();
            $table->foreignId('petugas_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengaduan');
    }
};
