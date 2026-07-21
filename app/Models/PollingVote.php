<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PollingVote extends Model
{
    use HasFactory;

    protected $table = 'polling_vote';

    protected $fillable = ['polling_id', 'polling_opsi_id', 'user_id'];

    // ── Relasi ───────────────────────────────────────────────────────────

    public function polling()
    {
        return $this->belongsTo(Polling::class);
    }

    public function opsi()
    {
        return $this->belongsTo(PollingOpsi::class, 'polling_opsi_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
