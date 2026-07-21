<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class KeuanganKas extends Model
{
    use HasFactory;

    protected $table = 'keuangan_kas';

    protected $fillable = ['nama', 'keterangan', 'saldo'];

    protected $casts = ['saldo' => 'decimal:2'];

    public function transaksi()
    {
        return $this->hasMany(KeuanganTransaksi::class, 'kas_id');
    }
}
