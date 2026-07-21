<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LombaPeserta extends Model
{
    protected $table = 'lomba_peserta';

    protected $fillable = [
        'lomba_id', 'nama_peserta', 'nomor_urut', 'kategori_usia', 'kontak', 'juara',
    ];

    public function lomba()
    {
        return $this->belongsTo(Lomba::class);
    }
}
