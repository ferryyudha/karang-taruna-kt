<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pengumuman extends Model
{
    use HasFactory;

    protected $table = 'pengumuman';

    protected $fillable = ['judul', 'isi', 'kategori', 'tanggal', 'status', 'user_id'];

    protected $casts = ['tanggal' => 'date'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'publish');
    }
}
