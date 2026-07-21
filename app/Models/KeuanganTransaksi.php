<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class KeuanganTransaksi extends Model
{
    use HasFactory;

    protected $table = 'keuangan_transaksi';

    protected $fillable = [
        'kas_id', 'kategori_id', 'tipe', 'jumlah', 'tanggal', 'keterangan', 'bukti_foto', 'user_id'
    ];

    protected $casts = [
        'tanggal' => 'date',
        'jumlah' => 'decimal:2'
    ];

    public function kas()
    {
        return $this->belongsTo(KeuanganKas::class, 'kas_id');
    }

    public function kategori()
    {
        return $this->belongsTo(KeuanganKategori::class, 'kategori_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
