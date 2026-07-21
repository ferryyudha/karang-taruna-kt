<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Lomba extends Model
{
    use HasFactory;

    protected $table = 'lomba';

    protected $fillable = [
        'kegiatan_id', 'nama', 'kategori', 'deskripsi', 'tanggal', 'waktu_mulai',
        'lokasi', 'penanggung_jawab', 'status', 'user_id',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($lomba) {
            if ($lomba->tanggal) {
                $today = now()->startOfDay();
                $tanggal = \Carbon\Carbon::parse($lomba->tanggal)->startOfDay();

                if ($tanggal->gt($today)) {
                    $lomba->status = 'persiapan';
                } elseif ($tanggal->lt($today)) {
                    $lomba->status = 'selesai';
                } else {
                    $lomba->status = 'berlangsung';
                }
            }
        });
    }

    public function getStatusAttribute(): string
    {
        if (!$this->tanggal) {
            return 'persiapan';
        }
        $today = now()->startOfDay();
        $tanggal = \Carbon\Carbon::parse($this->tanggal)->startOfDay();

        if ($tanggal->gt($today)) {
            return 'persiapan';
        } elseif ($tanggal->lt($today)) {
            return 'selesai';
        } else {
            return 'berlangsung';
        }
    }

    public function kegiatan()
    {
        return $this->belongsTo(Kegiatan::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function peralatan()
    {
        return $this->hasMany(LombaPeralatan::class);
    }

    public function peserta()
    {
        return $this->hasMany(LombaPeserta::class);
    }

    public function pemenang()
    {
        return $this->hasMany(LombaPeserta::class)->whereNotNull('juara')->orderBy('juara');
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'persiapan'   => 'Persiapan',
            'berlangsung' => 'Berlangsung',
            'selesai'     => 'Selesai',
            default       => $this->status,
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'persiapan'   => 'secondary',
            'berlangsung' => 'warning',
            'selesai'     => 'success',
            default       => 'secondary',
        };
    }

    // Persentase peralatan yang sudah "siap" — buat progress bar di tampilan
    public function getPeralatanProgressAttribute(): int
    {
        $total = $this->peralatan->count();
        if ($total === 0) return 0;
        $siap = $this->peralatan->where('status', 'siap')->count();
        return (int) round(($siap / $total) * 100);
    }
}
