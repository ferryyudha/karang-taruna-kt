<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lomba_peserta', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lomba_id')->constrained('lomba')->cascadeOnDelete();
            $table->string('nama_peserta'); // nama individu atau nama tim/kelompok
            $table->string('nomor_urut')->nullable();
            $table->string('kategori_usia')->nullable();
            $table->string('kontak')->nullable();
            // null = belum ada hasil. Diisi bebas misal "Juara 1", "Juara 2", "Harapan 1"
            $table->string('juara')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lomba_peserta');
    }
};
