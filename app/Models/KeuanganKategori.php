<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class KeuanganKategori extends Model
{
    use HasFactory;

    protected $table = 'keuangan_kategori';

    protected $fillable = ['nama', 'tipe'];

    public function transaksi()
    {
        return $this->hasMany(KeuanganTransaksi::class, 'kategori_id');
    }
}
