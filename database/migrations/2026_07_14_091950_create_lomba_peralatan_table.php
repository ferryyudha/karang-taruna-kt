<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lomba_peralatan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lomba_id')->constrained('lomba')->cascadeOnDelete();
            // Opsional: kalau alatnya memang ada di gudang inventaris, dikaitkan ke situ.
            // Kalau null, berarti alat ini belum ada di inventaris (perlu beli/pinjam dari luar).
            $table->foreignId('inventaris_id')->nullable()->constrained('inventaris')->nullOnDelete();
            $table->string('nama_alat');
            $table->unsignedInteger('jumlah_dibutuhkan')->default(1);
            $table->enum('status', ['perlu_beli', 'perlu_pinjam', 'tersedia', 'siap'])->default('perlu_beli');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lomba_peralatan');
    }
};
