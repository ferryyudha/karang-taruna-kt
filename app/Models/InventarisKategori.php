<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InventarisKategori extends Model
{
    use HasFactory;

    protected $table = 'inventaris_kategori';
    protected $fillable = ['nama', 'keterangan'];

    public function inventaris()
    {
        return $this->hasMany(Inventaris::class, 'kategori_id');
    }
}
