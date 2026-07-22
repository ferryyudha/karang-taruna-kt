<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class Polling extends Model
{
    use HasFactory;

    protected $table = 'polling';

    protected $fillable = [
        'judul', 'deskripsi', 'tipe', 'mulai_at', 'selesai_at',
        'status', 'tampil_publik', 'dibuat_oleh',
    ];

    protected $casts = [
        'mulai_at'      => 'datetime',
        'selesai_at'    => 'datetime',
        'tampil_publik' => 'boolean',
    ];

    // Relasi 

    public function opsi()
    {
        return $this->hasMany(PollingOpsi::class)->orderBy('urutan');
    }

    public function votes()
    {
        return $this->hasMany(PollingVote::class);
    }

    public function pembuatBy()
    {
        return $this->belongsTo(User::class, 'dibuat_oleh');
    }

    // Accessors 

    /**
     * Jumlah user unik yang sudah vote di polling ini
     */
    public function getTotalVoterAttribute(): int
    {
        return $this->votes()->distinct('user_id')->count('user_id');
    }

    /**
     * Apakah user tertentu sudah pernah vote?
     */
    public function sudahDivoteOleh(int $userId): bool
    {
        return $this->votes()->where('user_id', $userId)->exists();
    }

    /**
     * Label status untuk tampilan
     */
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'draft'   => 'Draft',
            'aktif'   => 'Aktif',
            'selesai' => 'Selesai',
            default   => ucfirst($this->status),
        };
    }

    /**
     * Variant badge design-system untuk status
     */
    public function getStatusVariantAttribute(): string
    {
        return match ($this->status) {
            'aktif'   => 'warning',
            'selesai' => 'success',
            default   => 'neutral',
        };
    }

    /**
     * Apakah polling sedang bisa divote?
     */
    public function getIsAktifAttribute(): bool
    {
        return $this->status === 'aktif'
            && now()->between($this->mulai_at, $this->selesai_at);
    }
}
