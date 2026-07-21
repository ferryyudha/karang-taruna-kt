<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ── Tabel Polling Utama ──────────────────────────────────────────
        Schema::create('polling', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->text('deskripsi')->nullable();
            $table->enum('tipe', ['single', 'multi'])->default('single');
            $table->dateTime('mulai_at');
            $table->dateTime('selesai_at');
            $table->enum('status', ['draft', 'aktif', 'selesai'])->default('draft');
            $table->boolean('tampil_publik')->default(false);
            $table->foreignId('dibuat_oleh')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });

        // ── Tabel Opsi / Pilihan ─────────────────────────────────────────
        Schema::create('polling_opsi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('polling_id')->constrained('polling')->cascadeOnDelete();
            $table->string('teks_opsi');
            $table->integer('urutan')->default(0);
            $table->timestamps();
        });

        // ── Tabel Vote ───────────────────────────────────────────────────
        Schema::create('polling_vote', function (Blueprint $table) {
            $table->id();
            $table->foreignId('polling_id')->constrained('polling')->cascadeOnDelete();
            $table->foreignId('polling_opsi_id')->constrained('polling_opsi')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->timestamps();

            // Satu user hanya boleh vote satu opsi yg sama satu kali
            $table->unique(['polling_id', 'polling_opsi_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('polling_vote');
        Schema::dropIfExists('polling_opsi');
        Schema::dropIfExists('polling');
    }
};
