<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Dokumentasi extends Model
{
    use HasFactory;

    protected $table = 'dokumentasi';

    protected $fillable = ['kegiatan_id', 'foto', 'keterangan'];

    public function kegiatan()
    {
        return $this->belongsTo(Kegiatan::class);
    }
}
