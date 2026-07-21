<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Menu extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'icon', 'url', 'parent_id', 'order', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_menu');
    }

    public function parent()
    {
        return $this->belongsTo(Menu::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Menu::class, 'parent_id')->orderBy('order');
    }
}
