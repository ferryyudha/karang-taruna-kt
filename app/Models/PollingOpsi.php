<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PollingOpsi extends Model
{
    use HasFactory;

    protected $table = 'polling_opsi';

    protected $fillable = ['polling_id', 'teks_opsi', 'urutan'];

    // Relasi 

    public function polling()
    {
        return $this->belongsTo(Polling::class);
    }

    public function votes()
    {
        return $this->hasMany(PollingVote::class);
    }

    // Accessors 

    /**
     * Jumlah vote untuk opsi ini
     */
    public function getJumlahVoteAttribute(): int
    {
        return $this->votes()->count();
    }

    /**
     * Persentase opsi ini dari total vote polling (0-100)
     */
    public function getPersentaseAttribute(): float
    {
        $total = $this->polling?->votes()->count() ?? 0;
        if ($total === 0) return 0;
        return round(($this->jumlah_vote / $total) * 100, 1);
    }
}
