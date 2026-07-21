<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'role_id', 'foto', 'phone', 'is_active',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function isAdmin(): bool
    {
        return $this->role && $this->role->slug === 'admin';
    }

    public function canAccess(string $menuSlug): bool
    {
        if ($this->isAdmin()) return true;
        if (!$this->role) return false;
        return $this->role->menus->contains('slug', $menuSlug);
    }

    public function getSidebarMenus()
    {
        if ($this->isAdmin()) {
            return Menu::where('is_active', true)->orderBy('order')->get();
        }
        if (!$this->role) return collect();
        return $this->role->menus()->where('is_active', true)->orderBy('order')->get();
    }
}
