<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InventarisPeminjaman extends Model
{
    use HasFactory;

    protected $table = 'inventaris_peminjaman';
    protected $fillable = [
        'inventaris_id', 'peminjam', 'kontak', 'jumlah',
        'tanggal_pinjam', 'tanggal_kembali_rencana', 'tanggal_kembali_aktual',
        'status', 'keterangan', 'user_id',
    ];

    protected $casts = [
        'tanggal_pinjam'           => 'date',
        'tanggal_kembali_rencana'  => 'date',
        'tanggal_kembali_aktual'   => 'date',
    ];

    public function inventaris()
    {
        return $this->belongsTo(Inventaris::class, 'inventaris_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'dipinjam'     => 'Dipinjam',
            'dikembalikan' => 'Dikembalikan',
            'terlambat'    => 'Terlambat',
            default        => '-',
        };
    }
}
