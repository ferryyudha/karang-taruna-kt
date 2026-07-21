<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LombaPeralatan extends Model
{
    protected $table = 'lomba_peralatan';

    protected $fillable = [
        'lomba_id', 'inventaris_id', 'nama_alat', 'jumlah_dibutuhkan', 'status', 'catatan',
    ];

    public function lomba()
    {
        return $this->belongsTo(Lomba::class);
    }

    public function inventaris()
    {
        return $this->belongsTo(Inventaris::class);
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'perlu_beli'   => 'Perlu Dibeli',
            'perlu_pinjam' => 'Perlu Dipinjam',
            'tersedia'     => 'Tersedia di Gudang',
            'siap'         => 'Siap Dipakai',
            default        => $this->status,
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'perlu_beli'   => 'danger',
            'perlu_pinjam' => 'warning',
            'tersedia'     => 'info',
            'siap'         => 'success',
            default        => 'secondary',
        };
    }
}
