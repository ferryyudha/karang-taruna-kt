<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lomba', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kegiatan_id')->constrained('kegiatan')->restrictOnDelete();
            $table->string('nama');
            $table->string('kategori')->nullable(); // misal: Anak-anak, Remaja, Umum
            $table->text('deskripsi')->nullable();
            $table->date('tanggal');
            $table->time('waktu_mulai')->nullable();
            $table->string('lokasi')->nullable();
            $table->string('penanggung_jawab')->nullable();
            $table->enum('status', ['persiapan', 'berlangsung', 'selesai'])->default('persiapan');
            $table->foreignId('user_id')->constrained()->restrictOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lomba');
    }
};
