<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Kegiatan extends Model
{
    use HasFactory;

    protected $table = 'kegiatan';

    protected $fillable = [
        'nama', 'deskripsi', 'tanggal', 'lokasi', 'status', 'foto_cover', 'user_id',
    ];

    protected $casts = ['tanggal' => 'date'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function dokumentasi()
    {
        return $this->hasMany(Dokumentasi::class);
    }

    public function getStatusAttribute(): string
    {
        if (!$this->tanggal) {
            return 'upcoming';
        }
        $today = now()->startOfDay();
        $tanggal = $this->tanggal->startOfDay();

        if ($tanggal->gt($today)) {
            return 'upcoming';
        } elseif ($tanggal->lt($today)) {
            return 'completed';
        } else {
            return 'ongoing';
        }
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'upcoming'  => 'Akan Datang',
            'ongoing'   => 'Berlangsung',
            'completed' => 'Selesai',
            default     => $this->status,
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'upcoming'  => 'primary',
            'ongoing'   => 'warning',
            'completed' => 'success',
            default     => 'secondary',
        };
    }
}
