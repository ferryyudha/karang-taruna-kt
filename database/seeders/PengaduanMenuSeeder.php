<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\Role;
use Illuminate\Database\Seeder;

class PengaduanMenuSeeder extends Seeder
{
    public function run(): void
    {
        $menu = Menu::firstOrCreate(
            ['slug' => 'pengaduan'],
            [
                'name'      => 'Pengaduan Warga',
                'icon'      => 'bi-exclamation-triangle',
                'url'       => '/admin/pengaduan',
                'order'     => 4,
                'is_active' => true,
            ]
        );

        $admin = Role::where('slug', 'admin')->first();
        if ($admin && !$admin->menus()->where('menu_id', $menu->id)->exists()) {
            $admin->menus()->attach($menu->id);
        }
    }
}
