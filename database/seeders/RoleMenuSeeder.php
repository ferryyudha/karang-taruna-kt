<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Menu;
use Illuminate\Database\Seeder;

class RoleMenuSeeder extends Seeder
{
    public function run(): void
    {
        // Create or get roles
        $admin = Role::firstOrCreate(
            ['slug' => 'admin'],
            [
                'name'        => 'Admin',
                'description' => 'Administrator dengan akses penuh ke semua menu',
            ]
        );

        $pengurus = Role::firstOrCreate(
            ['slug' => 'pengurus'],
            [
                'name'        => 'Pengurus',
                'description' => 'Pengurus umum Karang Taruna',
            ]
        );

        // Create or get menus
        $menus = [
            ['name' => 'Dashboard',        'slug' => 'dashboard',   'icon' => 'bi-speedometer2',  'url' => '/admin/dashboard',   'order' => 1],
            ['name' => 'Pengumuman',       'slug' => 'pengumuman',  'icon' => 'bi-megaphone',     'url' => '/admin/pengumuman',  'order' => 2],
            ['name' => 'Kegiatan',         'slug' => 'kegiatan',    'icon' => 'bi-calendar-event','url' => '/admin/kegiatan',    'order' => 3],
            ['name' => 'Dokumentasi',      'slug' => 'dokumentasi', 'icon' => 'bi-images',        'url' => '/admin/dokumentasi', 'order' => 4],
            ['name' => 'Anggota',          'slug' => 'anggota',     'icon' => 'bi-people',        'url' => '/admin/anggota',     'order' => 5],
            ['name' => 'Manajemen User',   'slug' => 'users',       'icon' => 'bi-person-gear',   'url' => '/admin/users',       'order' => 6],
            ['name' => 'Role & Akses',     'slug' => 'roles',       'icon' => 'bi-shield-lock',   'url' => '/admin/roles',       'order' => 7],
        ];

        $createdMenus = [];
        foreach ($menus as $menuData) {
            $createdMenus[$menuData['slug']] = Menu::firstOrCreate(
                ['slug' => $menuData['slug']],
                $menuData
            );
        }

        // Attach to Pengurus
        $pengurusMenuIds = [
            $createdMenus['dashboard']->id,
            $createdMenus['pengumuman']->id,
            $createdMenus['kegiatan']->id,
            $createdMenus['dokumentasi']->id,
        ];
        $pengurus->menus()->syncWithoutDetaching($pengurusMenuIds);

        // Attach to Admin
        $adminMenuIds = array_map(fn($m) => $m->id, $createdMenus);
        $admin->menus()->syncWithoutDetaching($adminMenuIds);
    }
}
