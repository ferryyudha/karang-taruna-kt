<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Anggota extends Model
{
    use HasFactory;

    protected $table = 'anggota';

    protected $fillable = [
        'nama', 'jabatan', 'foto', 'periode', 'bio', 'phone', 'email', 'urutan', 'aktif',
    ];

    protected $casts = ['aktif' => 'boolean'];

    public function iuran()
    {
        return $this->hasMany(KeuanganIuran::class, 'anggota_id');
    }
}
