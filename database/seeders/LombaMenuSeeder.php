<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\Role;
use Illuminate\Database\Seeder;

class LombaMenuSeeder extends Seeder
{
    public function run(): void
    {
        $menu = Menu::firstOrCreate(
            ['slug' => 'lomba'],
            [
                'name'      => 'Lomba',
                'icon'      => 'bi-trophy',
                'url'       => '/admin/lomba',
                'order'     => 3, // tampil setelah Kegiatan
                'is_active' => true,
            ]
        );

        // Geser urutan menu setelahnya biar Lomba nyempil rapi setelah Kegiatan
        Menu::where('slug', 'pengumuman')->update(['order' => 4]);
        Menu::where('slug', 'dokumentasi')->update(['order' => 5]);

        // Kasih akses otomatis ke role Admin (kalau ada)
        $admin = Role::where('slug', 'admin')->first();
        if ($admin) {
            $admin->menus()->syncWithoutDetaching([$menu->id]);
        }

        $this->command->info('Menu Lomba berhasil ditambahkan! Aktifkan akses buat role lain (misal Pengurus) lewat menu Manajemen Role.');
    }
}
