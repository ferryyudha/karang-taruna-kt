<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Inventaris extends Model
{
    use HasFactory;

    protected $table = 'inventaris';
    protected $fillable = [
        'kode', 'nama', 'kategori_id', 'jumlah_total', 'jumlah_tersedia',
        'kondisi', 'lokasi', 'tanggal_pengadaan', 'harga_satuan', 'keterangan', 'foto',
    ];

    protected $casts = [
        'tanggal_pengadaan' => 'date',
        'harga_satuan'      => 'decimal:2',
    ];

    public function kategori()
    {
        return $this->belongsTo(InventarisKategori::class, 'kategori_id');
    }

    public function peminjaman()
    {
        return $this->hasMany(InventarisPeminjaman::class, 'inventaris_id');
    }

    public function peminjamanAktif()
    {
        return $this->hasMany(InventarisPeminjaman::class, 'inventaris_id')
            ->whereIn('status', ['dipinjam', 'terlambat']);
    }

    public function getKondisiLabelAttribute(): string
    {
        return match ($this->kondisi) {
            'baik'         => 'Baik',
            'rusak_ringan' => 'Rusak Ringan',
            'rusak_berat'  => 'Rusak Berat',
            default        => '-',
        };
    }
}
